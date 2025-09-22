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

    'enabled' => env('GUARDIAN_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Master User Identifier
    |--------------------------------------------------------------------------
    |
    | This defines the master user who will receive email-based 2FA codes.
    | Can be a username, email, or any unique identifier from your users table.
    | Example: 'snawbar', 'admin@example.com', or 'master_user'
    |
    */

    'master_user' => env('GUARDIAN_MASTER_USER', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Master User Email Recipients
    |--------------------------------------------------------------------------
    |
    | These are the email addresses that will receive 2FA codes when a master
    | user attempts to login. You can specify multiple emails for redundancy.
    |
    */

    'master_emails' => [
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
];