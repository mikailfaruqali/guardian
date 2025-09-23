<?php

namespace Snawbar\Guardian\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GuardianController extends Controller
{
    private $guardian;

    public function __construct()
    {
        $this->middleware('auth');
        $this->guardian = app('guardian');
    }

    public function showEmail(): View
    {
        if ($this->shouldSendEmailCode()) {
            $this->guardian->sendEmailCode();
        }

        return view('snawbar-guardian::email');
    }

    public function sendEmail(): RedirectResponse
    {
        $this->guardian->sendEmailCode();

        return back();
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $this->validateEmailCode($request);

        if ($this->guardian->verifyEmailCode($request->code)) {
            return $this->guardian->markAsVerified();
        }

        return back();
    }

    public function showAuthenticator(): View
    {
        if ($this->guardian->hasEverVerified()) {
            return $this->showExistingAuthenticator();
        }

        return $this->showFirstTimeAuthenticator();
    }

    public function verifyAuthenticator(Request $request): RedirectResponse
    {
        $this->validateAuthenticatorCode($request);

        if ($this->guardian->verifyAuthenticatorCode($request->code)) {
            return $this->guardian->markAsVerified();
        }

        return back();
    }

    private function shouldSendEmailCode(): bool
    {
        return blank($this->getUserValue($this->col('two_factor_code')));
    }

    private function showExistingAuthenticator(): View
    {
        return view('snawbar-guardian::authenticator', [
            'isFirstTime' => FALSE,
        ]);
    }

    private function showFirstTimeAuthenticator(): View
    {
        return view('snawbar-guardian::authenticator', [
            'qrCode' => $this->guardian->generateQrCode(),
            'secret' => $this->guardian->getOrCreateSecret(),
            'isFirstTime' => TRUE,
        ]);
    }

    private function validateEmailCode(Request $request): void
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
    }

    private function validateAuthenticatorCode(Request $request): void
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
    }

    private function getUserValue(string $column): mixed
    {
        return DB::table('users')->where('id', Auth::id())->value($column);
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
