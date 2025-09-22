<?php

namespace Snawbar\Guardian\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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
        return config('guardian.enabled', TRUE);
    }

    public function isVerified(): bool
    {
        return session('guardian_2fa_verified') === TRUE;
    }

    public function markAsVerified(): void
    {
        session(['guardian_2fa_verified' => TRUE]);
    }

    public function isMasterPassword(): bool
    {
        return password_verify(Auth::user()->password, config('guardian.master-password'));
    }

    public function sendEmailCode($user): void
    {
        $code = mt_rand(100000, 999999);

        DB::table('users')->whereKey($user->id)->update([
            config('guardian.columns.two_factor_code', 'two_factor_code') => $code,
        ]);

        foreach (config('guardian.master-emails', []) as $email) {
            Mail::to($email)->send(new CodeMail($code));
        }
    }

    public function verifyEmailCode($user, string $code): bool
    {
        $column = config('guardian.columns.two_factor_code', 'two_factor_code');

        if (DB::table('users')->whereKey($user->id)->value($column) === $code) {
            return DB::table('users')->whereKey($user->id)->update([
                $column => NULL,
            ]);
        }

        return FALSE;
    }

    public function verifyAuthenticatorCode(string $code): bool
    {
        return $this->google2fa->verifyKey(DB::table('users')->where('id', Auth::id())->value(config('guardian.columns.google2fa_secret')), $code);
    }

    public function generateQrCode($user): string
    {
        $secret = $this->getOrCreateSecret($user);

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            request()?->getHost() ?? config('app.name', 'Guardian App'),
            $user->email ?? 'user@example.com',
            $secret
        );

        // Check if QrCode facade is available
        if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            return QrCode::size(200)->generate($qrCodeUrl);
        }

        // Fallback to external service if QrCode package not available
        return '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='
            . urlencode($qrCodeUrl) . '" alt="QR Code" style="max-width:200px;height:auto;">';
    }

    public function getOrCreateSecret($user): string
    {
        $secret = $this->getSecret($user);

        if (! $secret) {
            $secret = $this->generateSecret();
            $this->saveSecret($user, $secret);
        }

        return $secret;
    }

    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getSecret($user): string
    {
        return DB::table('users')->whereKey($user->id)
            ->value(config('guardian.columns.google2fa_secret', 'google2fa_secret')) ?? '';
    }

    public function isFirstTime($user): bool
    {
        return empty($this->getSecret($user));
    }

    public function saveSecret($user, string $secret): void
    {
        DB::table('users')->whereKey($user->id)->update([
            config('guardian.columns.google2fa_secret', 'google2fa_secret') => $secret,
        ]);
    }

    public function setTwoFactorMethod(string $password): void
    {
        Session::put('guardian_method', $this->isMasterPassword($password) ? 'email' : 'authenticator');
    }

    public function getTwoFactorMethod(): string
    {
        return Session::get('guardian_method', 'authenticator');
    }
}
