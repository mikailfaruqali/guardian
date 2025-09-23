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
    | Logo and Font Paths
    |--------------------------------------------------------------------------
    |
    | Custom logo and font paths for Guardian UI customization.
    | Logo path should be a public URL or asset path relative to public folder.
    | Font path can be a Google Fonts URL, local font file, or font stack.
    | Leave empty to use defaults (Guardian icon and system fonts).
    |
    */

    'logo-path' => env('GUARDIAN_LOGO_PATH', ''),

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
    ],
];
