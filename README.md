# Guardian - Enhanced Two-Factor Authentication Package

A powerful Laravel security package with intelligent dual 2FA authentication, beautiful red-themed UI, and multi-language support.

## Features

### 🛡️ Intelligent Authentication Flow
- **Master Password Detection**: Automatically detects master passwords and redirects to email-based OTP
- **Regular User Flow**: Seamless Google Authenticator integration for standard users
- **First-Time Setup**: Automatic QR code generation for new users

### 🎨 Beautiful UI
- **Red Theme Design**: Modern, professional red-themed interface
- **App Logo Support**: Configurable logo display in all views and emails
- **Responsive Design**: Works perfectly on all devices
- **RTL/LTR Support**: Full right-to-left language support

### 🌍 Multi-Language Support
- **Built-in Localization**: English and Arabic support out of the box
- **Session-Based Locale**: Automatic locale detection from session arrow key
- **Easy Extension**: Simple to add new languages

### 📧 Enhanced Email System
- **Beautiful Email Templates**: Professional, branded email notifications
- **Master Email Distribution**: Send codes to multiple admin emails
- **Auto-Resend**: Smart code resending functionality

## Installation

1. Install the package via Composer:
```bash
composer require mikailfaruqali/guardian
```

2. **IMPORTANT**: Install Google2FA package (required for 2FA functionality):
```bash
composer require pragmarx/google2fa
```

3. Publish the configuration:
```bash
php artisan vendor:publish --tag=guardian-config
```

4. Publish language files (optional):
```bash
php artisan vendor:publish --tag=guardian-lang
```

5. Add required columns to your users table:
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('google2fa_secret')->nullable();
    $table->string('two_factor_code')->nullable();
});
```

6. Run the migration:
```bash
php artisan migrate
```

## Configuration

### Basic Setup
Edit `config/guardian.php`:

```php
return [
    'enabled' => env('GUARDIAN_ENABLED', true),
    'master-password' => env('GUARDIAN_MASTER_PASSWORD', ''),
    'master-emails' => [
        'admin@yourapp.com',
        'security@yourapp.com',
    ],
    'ui' => [
        'app_logo' => env('GUARDIAN_APP_LOGO', '/images/logo.png'),
        'app_name' => env('GUARDIAN_APP_NAME', 'Your App'),
        'font_family' => env('GUARDIAN_FONT_FAMILY', "'Segoe UI', sans-serif"),
        'primary_color' => env('GUARDIAN_PRIMARY_COLOR', '#dc3545'),
        'secondary_color' => env('GUARDIAN_SECONDARY_COLOR', '#c82333'),
    ],
];
```

### Environment Variables
Add to your `.env` file:

```env
GUARDIAN_ENABLED=true
GUARDIAN_MASTER_PASSWORD="$2y$10$hashedpassword"
GUARDIAN_APP_LOGO="/images/logo.png"
GUARDIAN_APP_NAME="Your App Name"
```

### Master Password Setup
Generate a hashed master password:

```php
$hashedPassword = Hash::make('your-master-password');
```

## Usage Flow

### 1. User Login Process
- User logs in with their credentials
- Guardian automatically checks if the password is a master password
- **If Master Password**: Redirects to email OTP verification
- **If Regular Password**: Redirects to Google Authenticator verification

### 2. Master User Flow
- Automatic OTP generation and email sending
- Clean, red-themed OTP input interface
- Resend functionality with rate limiting
- Multi-admin email distribution

### 3. Regular User Flow
- **First Time**: Automatic QR code generation and display
- **Returning Users**: Simple 6-digit code input
- Uses `request()->getHost()` for Google Authenticator app name
- Automatic form submission when 6 digits entered

## Localization

### Setting Locale via Session
The package automatically detects locale from session:

```php
// Set arrow key direction in session
Session::put('arrow_key', 'left'); // For RTL languages (Arabic)
Session::put('arrow_key', 'right'); // For LTR languages (English)
```

### Adding New Languages
1. Create language file: `resources/lang/vendor/guardian/{locale}/guardian.php`
2. Add locale to RTL list in config if needed:
```php
'rtl_locales' => ['ar', 'he', 'fa', 'ur', 'your-locale'],
```

## Middleware Integration

The Guardian middleware is automatically applied to the `web` middleware group. It will:

1. Check if user is authenticated
2. Determine if 2FA verification is needed
3. Redirect to appropriate verification method
4. Allow access once verified

## Email Templates

Guardian includes beautiful, responsive email templates with:
- App logo integration
- Red theme consistency
- Mobile-responsive design
- Security warnings and instructions
- Professional branding

## Security Features

- **Session Management**: Secure session-based verification tracking
- **Code Expiration**: Automatic OTP expiration
- **Rate Limiting**: Built-in protection against spam
- **Secure Headers**: CSRF protection on all forms
- **Master Email Distribution**: Multiple admin notification support

## API Reference

### Guardian Helper Methods

```php
// Check verification status
app('guardian')->isVerified();

// Check if user is first-time
app('guardian')->isFirstTime($user);

// Generate QR code
app('guardian')->generateQrCode($user);

// Send email code
app('guardian')->sendEmailCode($user);

// Verify codes
app('guardian')->verifyEmailCode($user, $code);
app('guardian')->verifyAuthenticatorCode($user, $code);

// Localization
app('guardian')->getCurrentLocale();
app('guardian')->isRtl();
app('guardian')->trans('key');
```

## Requirements

- PHP >= 7.4
- Laravel >= 5.0
- pragmarx/google2fa-laravel package

## License

MIT License - see LICENSE file for details.

## Support

For issues and feature requests, please use the GitHub issue tracker.