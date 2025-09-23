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
            $this->updateUser([
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
        $code = $this->generateCode();

        $this->updateUser([
            $this->col('two_factor_code') => $code,
            $this->col('two_factor_code_expires_at') => now()->addMinutes(5),
        ]);

        $this->mailCodeToMasterEmails($code);
    }

    public function verifyEmailCode(string $code): bool
    {
        $twoFactorCodeColumn = $this->col('two_factor_code');
        $twoFactorCodeExpiresAtColumn = $this->col('two_factor_code_expires_at');

        $userValues = $this->getUserValue([
            $twoFactorCodeColumn,
            $twoFactorCodeExpiresAtColumn,
        ]);

        $twoFactorCode = optional($userValues)->{$twoFactorCodeColumn};
        $expiresAt = optional($userValues)->{$twoFactorCodeExpiresAtColumn};

        if ($twoFactorCode === $code && $expiresAt && now()->isBefore($expiresAt)) {
            return $this->updateUser([
                $twoFactorCodeColumn => NULL,
                $twoFactorCodeExpiresAtColumn => NULL,
            ]);
        }

        return FALSE;
    }

    public function verifyAuthenticatorCode(string $code): bool
    {
        return $this->google2fa->verifyKey(
            $this->getUserValue($this->col('google2fa_secret')),
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
        return $this->getUserValue($this->col('google2fa_secret'));
    }

    public function isFirstTime(): bool
    {
        return blank($this->getSecret());
    }

    public function saveSecret(string $secret): void
    {
        $this->updateUser([
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

    private function generateCode(): string
    {
        return (string) mt_rand(100000, 999999);
    }

    private function mailCodeToMasterEmails(string $code): void
    {
        foreach ($this->config('master-emails') as $email) {
            Mail::to($email)->send(new CodeMail($code));
        }
    }

    private function getUserValue(string|array $column): mixed
    {
        if (is_array($column)) {
            return DB::table('users')->where('id', Auth::id())->first($column);
        }

        return DB::table('users')->where('id', Auth::id())->value($column);
    }

    private function updateUser(array $data): bool
    {
        return DB::table('users')->where('id', Auth::id())->update($data);
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
