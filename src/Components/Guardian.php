<?php

namespace Snawbar\Guardian\Components;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Snawbar\Guardian\Mail\CodeMail;

class Guardian
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA;
    }

    public function isEnabled(): bool
    {
        return $this->config('enabled');
    }

    public function isVerified(): bool
    {
        return session('guardian_2fa_verified') === TRUE;
    }

    public function hasEverVerified(): bool
    {
        return DB::table('users')->where('id', Auth::id())->value($this->col('google2fa_verified'));
    }

    public function markAsVerified(): RedirectResponse
    {
        if (! $this->hasEverVerified()) {
            DB::table('users')->where('id', Auth::id())->update([
                $this->col('google2fa_verified') => TRUE,
            ]);
        }

        session(['guardian_2fa_verified' => TRUE]);

        return redirect()->intended('/');
    }

    public function isMasterPassword(): bool
    {
        return password_verify(session('guardian_master_password'), $this->config('master-password'));
    }

    public function setMasterPassword(Request $request): void
    {
        session(['guardian_master_password' => $request->input('password')]);
    }

    public function sendEmailCode(): void
    {
        $code = mt_rand(100000, 999999);

        DB::table('users')->where('id', Auth::id())->update([
            $this->col('two_factor_code') => $code,
        ]);

        foreach ($this->config('master-emails') as $email) {
            Mail::to($email)->send(new CodeMail($code));
        }
    }

    public function verifyEmailCode(string $code): bool
    {
        $column = $this->col('two_factor_code');

        if (DB::table('users')->where('id', Auth::id())->value($column) === $code) {
            return DB::table('users')->where('id', Auth::id())->update([$column => NULL]);
        }

        return FALSE;
    }

    public function verifyAuthenticatorCode(string $code): bool
    {
        return $this->google2fa->verifyKey(
            DB::table('users')->where('id', Auth::id())->value($this->col('google2fa_secret')),
            $code
        );
    }

    public function generateQrCode(): string
    {
        $secret = $this->getOrCreateSecret();

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            request()?->getHost(),
            Auth::user()->email,
            $secret
        );

        return QrCode::size(200)->generate($qrCodeUrl);
    }

    public function getOrCreateSecret(): string
    {
        $secret = $this->getSecret();

        if (blank($secret)) {
            $secret = $this->generateSecret();
            $this->saveSecret($secret);
        }

        return $secret;
    }

    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getSecret(): ?string
    {
        return DB::table('users')->where('id', Auth::id())->value($this->col('google2fa_secret'));
    }

    public function isFirstTime(): bool
    {
        return blank($this->getSecret());
    }

    public function saveSecret(string $secret): void
    {
        DB::table('users')->where('id', Auth::id())->update([
            $this->col('google2fa_secret') => $secret,
        ]);
    }

    public function setTwoFactorMethod(): void
    {
        session(['guardian_method' => $this->isMasterPassword() ? 'email' : 'authenticator']);
    }

    public function getTwoFactorMethod(): string
    {
        return session('guardian_method');
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
