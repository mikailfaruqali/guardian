<?php

namespace Snawbar\Guardian\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Snawbar\Guardian\Mail\TwoFactorCodeMail;

class Guardian
{
    protected $authenticator;

    public function __construct()
    {
        $this->authenticator = app(Authenticator::class);
    }

    /**
     * Check if user is a master user
     */
    public function isMasterUser($user): bool
    {
        if (!$user) {
            return false;
        }

        $masterUser = config('guardian.master_user');
        
        // Check by username or email
        return $user->email === $masterUser || 
               $user->name === $masterUser || 
               (isset($user->username) && $user->username === $masterUser);
    }

    /**
     * Check if 2FA is enabled
     */
    public function isEnabled(): bool
    {
        return config('guardian.enabled', true);
    }

    /**
     * Check if user has verified 2FA in current session
     */
    public function isVerified(): bool
    {
        return Session::get('guardian_2fa_verified') === true && 
               Session::get('guardian_2fa_user') === Auth::id();
    }

    /**
     * Mark user as verified in session
     */
    public function markAsVerified(): void
    {
        Session::put('guardian_2fa_verified', true);
        Session::put('guardian_2fa_user', Auth::id());
    }

    /**
     * Send email code to master user
     */
    public function sendEmailCode($user): bool
    {
        if (!$this->isMasterUser($user)) {
            return false;
        }

        $code = rand(100000, 999999);
        $codeColumn = config('guardian.columns.two_factor_code', 'two_factor_code');
        
        // Store in user's column
        $user->update([$codeColumn => $code]);

        // Send to all master emails
        $masterEmails = config('guardian.master_emails', []);
        
        try {
            foreach ($masterEmails as $email) {
                Mail::to($email)->send(new TwoFactorCodeMail($code));
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify email code for master user
     */
    public function verifyEmailCode($user, string $code): bool
    {
        if (!$this->isMasterUser($user)) {
            return false;
        }

        $codeColumn = config('guardian.columns.two_factor_code', 'two_factor_code');
        
        if ($user->$codeColumn == $code) {
            $user->update([$codeColumn => null]);
            return true;
        }

        return false;
    }

    /**
     * Verify Google Authenticator code for regular user
     */
    public function verifyAuthenticatorCode($user, string $code): bool
    {
        $secretColumn = config('guardian.columns.google2fa_secret', 'google2fa_secret');
        
        if ($this->isMasterUser($user) || !$user->$secretColumn) {
            return false;
        }

        return $this->authenticator->verifyGoogle2FA($user->$secretColumn, $code);
    }

    /**
     * Generate QR code for Google Authenticator
     */
    public function generateQrCode($user): string
    {
        if ($this->isMasterUser($user)) {
            return '';
        }

        $secretColumn = config('guardian.columns.google2fa_secret', 'google2fa_secret');
        $secret = $user->$secretColumn ?: $this->generateSecret();
        $issuer = request() ? request()->getHost() : config('app.name', 'Laravel App');
        
        return $this->authenticator->getQRCodeInline(
            $issuer,
            $user->email,
            $secret
        );
    }

    /**
     * Generate secret for Google Authenticator
     */
    public function generateSecret(): string
    {
        return $this->authenticator->generateSecretKey();
    }

    /**
     * Check if user needs to setup Google Authenticator
     */
    public function needsSetup($user): bool
    {
        $secretColumn = config('guardian.columns.google2fa_secret', 'google2fa_secret');
        return !$this->isMasterUser($user) && empty($user->$secretColumn);
    }
}