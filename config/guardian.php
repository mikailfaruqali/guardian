<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guardian Two Factor Authentication Status
    |--------------------------------------------------------------------------
    |
    | This option controls whether the Guardian 2FA system is active or not.
    | When disabled, all 2FA checks will be bypassed completely.
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
    |
    */

    'master-emails' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Column Names
    |--------------------------------------------------------------------------
    |
    | These are the column names in your users table that Guardian will use.
    | Make sure these columns exist in your users table:
    | - google2fa_secret: stores Google Authenticator secret (string, nullable)
    | - two_factor_code: stores temporary email codes (string, nullable)
    |
    */

    'columns' => [
        'google2fa_secret' => 'google2fa_secret',
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
    |
    */

    'skipped-routes' => [
        'login',
        'guardian',
    ],
];
