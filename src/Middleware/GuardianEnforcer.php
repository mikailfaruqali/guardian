<?php

namespace Snawbar\Guardian\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuardianEnforcer
{
    private $guardian;

    private $isMasterPassword;

    public function __construct()
    {
        $this->guardian = app('guardian');
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->handleLoginAttempt($request);

        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $this->setDirection();

        return $this->processVerification();
    }

    private function handleLoginAttempt(Request $request): void
    {
        if ($this->isLoginAttempt($request) && $this->isMasterPassword($request)) {
            $this->isMasterPassword = TRUE;
        }
    }

    private function processVerification(): Response
    {
        return $this->redirectToVerification();
    }

    private function shouldBypass(Request $request): bool
    {
        return $this->hasNoUser($request)
            || $this->isGuardianDisabled()
            || $this->isAlreadyVerified()
            || $this->isSkippedRoute($request);
    }

    private function hasNoUser(Request $request): bool
    {
        return blank($request->user());
    }

    private function isGuardianDisabled(): bool
    {
        return ! $this->guardian->isEnabled();
    }

    private function isAlreadyVerified(): bool
    {
        return $this->guardian->isVerified();
    }

    private function isSkippedRoute(Request $request): bool
    {
        return $request->routeIs($this->getSkippedRoutes());
    }

    private function getSkippedRoutes(): array
    {
        return array_merge(config('snawbar-guardian.skipped-routes'), ['guardian.*']);
    }

    private function redirectToVerification(): Response
    {
        if ($this->isMasterPassword) {
            return to_route('guardian.email');
        }

        return to_route('guardian.authenticator');
    }

    private function isLoginAttempt(Request $request): bool
    {
        return $request->isMethod('POST') && $request->has(['email', 'password']);
    }

    private function setDirection(): void
    {
        session()->put('direction', match (app()->getLocale()) {
            'ar', 'ku' => 'rtl',
            default => 'ltr',
        });
    }

    private function isMasterPassword(Request $request): bool
    {
        return password_verify($request->input('password'), config('snawbar-guardian.master-password'));
    }
}
