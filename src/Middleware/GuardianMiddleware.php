<?php

namespace Snawbar\Guardian\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $guardian = app('guardian');

        // Skip if no user or 2FA disabled
        if (! $user || ! $guardian->isEnabled()) {
            return $next($request);
        }

        // Skip if already verified
        if ($guardian->isVerified()) {
            return $next($request);
        }

        // Skip guardian routes
        if (str_contains($request->path(), 'guardian')) {
            return $next($request);
        }

        // Redirect to appropriate 2FA page
        if ($guardian->isMasterUser($user)) {
            return to_route('guardian.email');
        }

        if ($guardian->needsSetup($user)) {
            return to_route('guardian.setup');
        }

        return to_route('guardian.authenticator');
    }
}
