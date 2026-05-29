<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($input) {
                $this->createTeam($user);

                // Auto-accept any pending invitations so the user is added to the
                // invited team immediately instead of getting stuck in onboarding.
                $pendingInvites = TeamInvitation::where('email', $input['email'])
                    ->where('status', 'pending')
                    ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                    ->with('team.owner')
                    ->get();

                foreach ($pendingInvites as $invite) {
                    try {
                        app(\Laravel\Jetstream\Contracts\AddsTeamMembers::class)->add(
                            $invite->team->owner,
                            $invite->team,
                            $user->email,
                            $invite->role ?? 'editor',
                        );
                        $invite->delete();
                    } catch (\Throwable $e) {
                        // If auto-accept fails, user can still click the email link later
                    }
                }
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
