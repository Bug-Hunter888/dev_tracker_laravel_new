<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_onboarded) {
            // Don't intercept the invitation acceptance URL — let it run so
            // AddTeamMember can mark the user as onboarded before the dashboard redirect.
            if ($request->routeIs('team-invitations.*')) {
                return $next($request);
            }

            return redirect()->route('onboarding.welcome');
        }

        return $next($request);
    }
}
