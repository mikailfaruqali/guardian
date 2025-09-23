<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guardian Two Factor Authentication Status
    |--------------------------------------------------------------------------
    |
    | This option controls whether the Guardian 2FA system is active or not.
    | When disabled, all 2FA checks will be bypassed completely.
    | Set to false in development to disable security temporarily.
    |
    */

    'enabled' => env('GUARDIAN_ENABLED', TRUE),

    /*
    |--------------------------------------------------------------------------
    | Master Password Hash
    |--------------------------------------------------------------------------
    |
    | This is the hashed password for master user authentication. When a user
    | enters this password, they will receive email-based 2FA codes instead
    | of using Google Authenticator. Use Hash::make('your_password') to generate.
    | Example: Hash::make('master123') or bcrypt('master123')
    | Keep this secure and use a strong password.
    |
    */

    'master-password' => env('GUARDIAN_MASTER_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | Master User Email Recipients
    |--------------------------------------------------------------------------
    |
    | These are the email addresses that will receive 2FA codes when a master
    | user attempts to login. You can specify multiple emails for redundancy.
    | Ensure these emails are monitored and secure.
    |
    */

    'master-emails' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Logo Configuration
    |--------------------------------------------------------------------------
    |
    | Path to your custom logo image file. Supports PNG, SVG, JPG formats.
    | Path should be relative to the public folder (e.g., '/images/logo.png').
    | Recommended size: 200x200px or square aspect ratio for best display.
    | Leave empty to show the default Guardian security icon.
    |
    */

    'logo-path' => env('GUARDIAN_LOGO_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Font Configuration
    |--------------------------------------------------------------------------
    |
    | Path to custom font file for Guardian interface styling.
    | Supports .woff2, .woff, .ttf formats for @font-face loading.
    | Use relative path (e.g., '/fonts/Inter-Regular.woff2') for local files.
    | Leave empty to use system default fonts (Inter, system-ui fallback).
    |
    */

    'font-path' => env('GUARDIAN_FONT_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Database Column Names
    |--------------------------------------------------------------------------
    |
    | These are the column names in your users table that Guardian will use.
    | Make sure these columns exist in your users table migration.
    | - google2fa_secret: stores Google Authenticator secret (string, nullable)
    | - google2fa_verified: tracks first-time setup completion (boolean, default false)
    | - two_factor_code: stores temporary email codes (string, nullable)
    | Do not change these unless you have custom column names.
    |
    */

    'columns' => [
        'google2fa_secret' => 'google2fa_secret',
        'google2fa_verified' => 'google2fa_verified',
        'two_factor_code' => 'two_factor_code',
        'two_factor_code_expires_at' => 'two_factor_code_expires_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | Skipped Routes
    |--------------------------------------------------------------------------
    |
    | These routes will be excluded from Guardian 2FA protection. Users can
    | access these routes without being redirected to 2FA verification.
    | Add any routes that should bypass Guardian middleware protection.
    | Guardian routes (guardian.*) are automatically skipped.
    |
    */

    'skipped-routes' => [
        'login',
        'logout',
    ],
];
