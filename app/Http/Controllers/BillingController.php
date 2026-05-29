<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BillingController extends Controller
{
    public function upgrade(Request $request)
    {
        $team = auth()->user()->currentTeam;

        if (!$team) {
            return redirect()->route('dashboard');
        }

        // Allow pre-selecting a plan via ?plan=pro or ?plan=team
        $preselect = in_array($request->query('plan'), ['pro', 'team'])
            ? $request->query('plan')
            : 'pro';

        return view('billing.upgrade', compact('team', 'preselect'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => ['required', 'in:pro,team'],
        ]);

        $team = auth()->user()->currentTeam;

        if (!$team) {
            return redirect()->route('dashboard');
        }

        $plan   = $request->plan;
        $amount = $plan === 'pro' ? '$9.00' : '$29.00';
        $ref    = 'DT-' . strtoupper(substr(md5(uniqid()), 0, 10));

        $team->update(['plan' => $plan]);

        // Send confirmation email to the user
        try {
            Mail::to(auth()->user()->email)->send(new SubscriptionConfirmed(
                user:           auth()->user(),
                plan:           $plan,
                teamName:       $team->name,
                transactionRef: $ref,
                amount:         $amount,
            ));
        } catch (\Exception $e) {
            // Don't block the redirect if mail fails
        }

        return redirect()->route('billing.success')
            ->with('subscribed_plan', $plan)
            ->with('transaction_ref', $ref);
    }

    public function success()
    {
        $plan = session('subscribed_plan', 'pro');
        $ref  = session('transaction_ref', 'DT-DEMO');

        return view('billing.success', compact('plan', 'ref'));
    }
}
