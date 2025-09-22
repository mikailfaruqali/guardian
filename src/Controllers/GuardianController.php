<?php

namespace Snawbar\Guardian\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    protected $guardian;

    public function __construct()
    {
        $this->middleware('auth');
        $this->guardian = app('guardian');
    }

    // Email verification for master users
    public function showEmail()
    {
        return view('guardian::email');
    }

    public function sendEmail()
    {
        $user = Auth::user();

        if ($this->guardian->sendEmailCode($user)) {
            return back()->with('success', 'Code sent to your email!');
        }

        return back()->with('error', 'Failed to send code');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = Auth::user();

        if ($this->guardian->verifyEmailCode($user, $request->code)) {
            $this->guardian->markAsVerified();

            return redirect()->intended('/');
        }

        return back()->with('error', 'Invalid code');
    }

    // Google Authenticator for regular users
    public function showAuthenticator()
    {
        return view('guardian::authenticator');
    }

    public function verifyAuthenticator(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = Auth::user();

        if ($this->guardian->verifyAuthenticatorCode($user, $request->code)) {
            $this->guardian->markAsVerified();

            return redirect()->intended('/');
        }

        return back()->with('error', 'Invalid code');
    }

    // Setup Google Authenticator
    public function showSetup()
    {
        $user = Auth::user();
        $qrCode = $this->guardian->generateQrCode($user);
        $secret = $user->google2fa_secret ?: $this->guardian->generateSecret();

        return view('guardian::setup', ['qrCode' => $qrCode, 'secret' => $secret]);
    }

    public function completeSetup(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = Auth::user();
        $secretColumn = config('guardian.columns.google2fa_secret', 'google2fa_secret');

        if (! $user->{$secretColumn}) {
            $user->update([$secretColumn => $this->guardian->generateSecret()]);
        }

        if ($this->guardian->verifyAuthenticatorCode($user, $request->code)) {
            $this->guardian->markAsVerified();

            return redirect('/')->with('success', '2FA setup complete!');
        }

        return back()->with('error', 'Invalid code');
    }
}
