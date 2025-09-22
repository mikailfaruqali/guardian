<?php

namespace Snawbar\Guardian\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuardianMiddleware
{
    protected $guardian;

    public function __construct()
    {
        $this->guardian = app('guardian');
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $this->guardian->setTwoFactorMethod();

        return $this->redirectToVerification();
    }

    protected function shouldBypass(Request $request): bool
    {
        return blank($request->user())
            || ! $this->guardian->isEnabled()
            || $this->guardian->isVerified()
            || $this->isSkippedRoute($request);
    }

    protected function isSkippedRoute(Request $request): bool
    {
        return str($request->path())->contains(config('snawbar-guardian.skipped-routes'));
    }

    protected function redirectToVerification()
    {
        return to_route(sprintf('guardian.%s', $this->guardian->getTwoFactorMethod()));
    }
}
