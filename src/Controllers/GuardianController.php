<?php

namespace Snawbar\Guardian\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuardianController extends Controller
{
    protected $guardian;

    public function __construct()
    {
        $this->middleware('auth');
        $this->guardian = app('guardian');
    }

    public function showEmail()
    {
        if ($this->guardian->getTwoFactorMethod() !== 'email') {
            return redirect()->route('guardian.authenticator');
        }

        $user = Auth::user();
        $codeColumn = config('guardian.columns.two_factor_code', 'two_factor_code');

        // Auto-send code on first visit if not already sent
        $hasCode = DB::table('users')->where('id', $user->id)->value($codeColumn);
        if (! $hasCode) {
            $this->guardian->sendEmailCode($user);
        }

        return view('snawbar-guardian::email');
    }

    public function sendEmail()
    {
        $user = Auth::user();

        if ($this->guardian->sendEmailCode($user)) {
            return back()->with('success', 'Code sent to email!');
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

        return back()->with('error', 'Invalid verification code!');
    }

    // Google Authenticator (when regular password used)
    public function showAuthenticator()
    {
        if ($this->guardian->getTwoFactorMethod() === 'email') {
            return redirect()->route('guardian.email');
        }

        $user = Auth::user();
        $isFirstTime = $this->guardian->isFirstTime($user);

        // First time - generate and show QR code setup
        if ($isFirstTime) {
            $secret = $this->guardian->getOrCreateSecret($user);
            $qrCode = $this->guardian->generateQrCode($user);

            return view('snawbar-guardian::authenticator', [
                'qrCode' => $qrCode,
                'secret' => $secret,
                'isFirstTime' => TRUE,
            ]);
        }

        // Returning user - just show input
        return view('snawbar-guardian::authenticator');
    }

    public function verifyAuthenticator(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = Auth::user();

        if ($this->guardian->verifyAuthenticatorCode($user, $request->code)) {
            $this->guardian->markAsVerified();

            return redirect()->intended('/');
        }

        return back()->with('error', 'Invalid verification code!');
    }
}
