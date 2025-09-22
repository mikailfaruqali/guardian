<?php

namespace Snawbar\Guardian\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuardianController extends Controller
{
    private $guardian;

    public function __construct()
    {
        $this->middleware('auth');
        $this->guardian = app('guardian');
    }

    public function showEmail()
    {
        if (blank(DB::table('users')->where('id', Auth::id())->value($this->col('two_factor_code')))) {
            $this->guardian->sendEmailCode();
        }

        return view('snawbar-guardian::email');
    }

    public function sendEmail()
    {
        $this->guardian->sendEmailCode();

        return back();
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($this->guardian->verifyEmailCode($request->code)) {
            return $this->guardian->markAsVerified();
        }

        return back();
    }

    public function showAuthenticator()
    {
        if ($this->guardian->hasEverVerified()) {
            return view('snawbar-guardian::authenticator', [
                'isFirstTime' => FALSE,
            ]);
        }

        return view('snawbar-guardian::authenticator', [
            'qrCode' => $this->guardian->generateQrCode(),
            'secret' => $this->guardian->getOrCreateSecret(),
            'isFirstTime' => TRUE,
        ]);
    }

    public function verifyAuthenticator(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($this->guardian->verifyAuthenticatorCode($request->code)) {
            return $this->guardian->markAsVerified();
        }

        return back();
    }

    private function config(string $key): mixed
    {
        return config(sprintf('snawbar-guardian.%s', $key));
    }

    private function col(string $key): string
    {
        return $this->config(sprintf('columns.%s', $key));
    }
}
