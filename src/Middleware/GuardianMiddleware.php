<?php

namespace Snawbar\Guardian\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        // Capture password from login request if available and user just logged in
        if ($request->isMethod('post') && $request->has('password') && $request->user()) {
            Session::put('guardian_login_password', $request->input('password'));
            Session::forget('guardian_method'); // Reset method for new login
        }

        // Check if we need to determine 2FA method first
        if (! Session::has('guardian_method') && $request->user()) {
            $password = Session::get('guardian_login_password');

            if ($password) {
                if ($this->guardian->isMasterPassword($password)) {
                    $this->guardian->setTwoFactorMethod($password);
                    $user = $request->user();
                    $this->guardian->sendEmailCode($user);
                } else {
                    $this->guardian->setTwoFactorMethod($password);
                }
            } else {
                // Default to authenticator if no password captured
                $this->guardian->setTwoFactorMethod('default');
            }
        }

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
        return str($request->path())->contains(config('guardian.skipped-routes'));
    }

    protected function redirectToVerification()
    {
        return redirect()->route(sprintf('guardian.%s', $this->guardian->getTwoFactorMethod()));
    }
}
