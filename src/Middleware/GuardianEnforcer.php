<?php

namespace Snawbar\Guardian\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuardianEnforcer
{
    private $guardian;

    public function __construct()
    {
        $this->guardian = app('guardian');
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->isLoginAttempt($request)) {
            $this->guardian->setMasterPassword($request);
        }

        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $this->guardian->setTwoFactorMethod();

        return $this->redirectToVerification();
    }

    private function shouldBypass(Request $request): bool
    {
        return blank($request->user())
            || ! $this->guardian->isEnabled()
            || $this->guardian->isVerified()
            || $this->isSkippedRoute($request);
    }

    private function isSkippedRoute(Request $request): bool
    {
        return $request->routeIs(array_merge(config('snawbar-guardian.skipped-routes'), ['guardian.*']));
    }

    private function redirectToVerification()
    {
        return to_route(sprintf('guardian.%s', $this->guardian->getTwoFactorMethod()));
    }

    private function isLoginAttempt(Request $request): bool
    {
        return $request->has(['email', 'password']);
    }
}
