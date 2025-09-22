# 🛡️ Guardian - Laravel Two-Factor Authentication Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mikailfaruqali/guardian.svg?style=flat-square)](https://packagist.org/packages/mikailfaruqali/guardian)
[![Total Downloads](https://img.shields.io/packagist/dt/mikailfaruqali/guardian.svg?style=flat-square)](https://packagist.org/packages/mikailfaruqali/guardian)
[![License](https://img.shields.io/packagist/l/mikailfaruqali/guardian.svg?style=flat-square)](https://packagist.org/packages/mikailfaruqali/guardian)

Guardian is a powerful Laravel security package that provides **dual two-factor authentication (2FA)** with beautiful UI and multi-language support. It offers **email-based 2FA for master users** and **Google Authenticator for regular users**, ensuring flexible security for different user types.

## ✨ Features

### 🔐 Dual Authentication System
- **Master Password Authentication** → Email-based 2FA codes
- **Regular Users** → Google Authenticator 2FA
- **Automatic detection** of authentication method
- **Session-based verification** management

### 📧 Email System
- **Gmail-optimized** email templates
- **Multi-language support** (English, Kurdish, Arabic)
- **HTML email templates** with beautiful design
- **Proper email headers** for deliverability
- **Emoji support** for visual appeal

### 🌍 Internationalization
- **English** (en) - Primary language
- **Kurdish** (ku) - Right-to-left (RTL) support
- **Arabic** (ar) - Right-to-left (RTL) support
- **Dynamic language detection**
- **Localized success/error messages**

### 🎨 Modern UI
- **Tailwind CSS** design system
- **Layout-based architecture** for consistency
- **Responsive design** for all devices
- **RTL support** for Arabic/Kurdish
- **Clean, professional interface**

### 🔧 Advanced Features
- **QR Code generation** with local fallback
- **Middleware protection** for routes
- **Configurable routes** and settings
- **Database integration** with existing users table
- **Session management** and verification
- **Error handling** with localized messages

## 📦 Installation

### 1. Install via Composer

```bash
composer require mikailfaruqali/guardian
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=snawbar-guardian-config
```

### 3. Publish Views (Optional)

```bash
php artisan vendor:publish --tag=snawbar-guardian-views
```

### 4. Publish Translations (Optional)

```bash
php artisan vendor:publish --tag=snawbar-guardian-lang
```

### 5. Database Migration

Add these columns to your `users` table:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('google2fa_secret')->nullable();
    $table->boolean('google2fa_verified')->default(false);
    $table->string('two_factor_code')->nullable();
});
```

Or run this migration:

```bash
php artisan make:migration add_guardian_columns_to_users_table
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuardianColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google2fa_secret')->nullable();
            $table->boolean('google2fa_verified')->default(false);
            $table->string('two_factor_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google2fa_secret', 'google2fa_verified', 'two_factor_code']);
        });
    }
}
```

## ⚙️ Configuration

### Basic Configuration (`config/guardian.php`)

```php
<?php

return [
    // Enable/Disable Guardian 2FA
    'enabled' => env('GUARDIAN_ENABLED', true),

    // Master password for email-based 2FA
    'master-password' => env('GUARDIAN_MASTER_PASSWORD', ''),

    // Email recipients for master users
    'master-emails' => [
        'admin@example.com',
        'security@example.com',
    ],

    // Database column mapping
    'columns' => [
        'google2fa_secret' => 'google2fa_secret',
        'google2fa_verified' => 'google2fa_verified',
        'two_factor_code' => 'two_factor_code',
    ],

    // Routes to skip Guardian protection
    'skipped-routes' => [
        'login',
        'guardian',
    ],
];
```

### Environment Variables (`.env`)

```env
# Guardian Configuration
GUARDIAN_ENABLED=true
GUARDIAN_MASTER_PASSWORD="$2y$10$your_hashed_password_here"

# Email Configuration (Important for Gmail delivery)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Your App Name"
```

### Generate Master Password Hash

```php
// In Laravel Tinker
php artisan tinker

// Generate password hash
bcrypt('your-master-password')
// or
Hash::make('your-master-password')
```

## 🚀 Usage

### 1. Automatic Middleware Registration

Guardian automatically registers middleware for web routes. All authenticated routes will require 2FA verification.

### 2. Authentication Flow

#### For Master Users:
1. User logs in with master password
2. Guardian detects master password
3. Sends 6-digit code to configured emails
4. User enters code to complete authentication

#### For Regular Users:
1. User logs in normally
2. Guardian redirects to Google Authenticator setup (first time)
3. User scans QR code with authenticator app
4. User enters 6-digit code to verify

### 3. Manual Routes (if needed)

```php
// In your routes/web.php
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/guardian/email', [GuardianController::class, 'showEmail'])->name('guardian.email');
    Route::post('/guardian/email/verify', [GuardianController::class, 'verifyEmail'])->name('guardian.email.verify');
    Route::get('/guardian/authenticator', [GuardianController::class, 'showAuthenticator'])->name('guardian.authenticator');
    Route::post('/guardian/authenticator/verify', [GuardianController::class, 'verifyAuthenticator'])->name('guardian.authenticator.verify');
});
```

## 📁 Package Structure

```
guardian/
├── config/
│   └── guardian.php                 # Main configuration file
├── lang/                           # Language files
│   ├── en/
│   │   └── guardian.php            # English translations
│   ├── ku/
│   │   └── guardian.php            # Kurdish translations (RTL)
│   └── ar/
│       └── guardian.php            # Arabic translations (RTL)
├── routes/
│   └── web.php                     # Package routes
├── src/
│   ├── Components/
│   │   └── Guardian.php            # Core Guardian component
│   ├── Controllers/
│   │   └── GuardianController.php  # Main controller
│   ├── Mail/
│   │   └── CodeMail.php            # Email template class
│   ├── Middleware/
│   │   └── GuardianEnforcer.php    # Authentication middleware
│   └── GuardianServiceProvider.php # Service provider
└── views/
    ├── layout.blade.php            # Base Tailwind layout
    ├── authenticator.blade.php     # Google Authenticator page
    ├── email.blade.php             # Email verification page
    └── mail/
        └── code.blade.php          # Email template
```

## 🎨 UI Components

### Layout System

Guardian uses a layout-based architecture with Tailwind CSS:

```blade
{{-- Base layout with Tailwind CSS --}}
@extends('snawbar-guardian::layout')

@section('title', 'Guardian Security')
@section('heading', 'Two-Factor Authentication')
@section('subtitle', 'Enter your verification code')

@section('content')
    {{-- Your content here --}}
@endsection
```

### Responsive Design

- **Mobile-first** approach
- **Tailwind CSS** utility classes
- **RTL support** for Arabic/Kurdish
- **Cross-browser** compatibility

## 🌍 Internationalization

### Supported Languages

| Language | Code | Direction | Status |
|----------|------|-----------|---------|
| English  | `en` | LTR       | ✅ Complete |
| Kurdish  | `ku` | RTL       | ✅ Complete |
| Arabic   | `ar` | RTL       | ✅ Complete |

### Adding New Languages

1. Create language file: `lang/{locale}/guardian.php`
2. Copy structure from `lang/en/guardian.php`
3. Translate all strings
4. Update layout for RTL if needed

### Language Detection

Guardian automatically detects the application locale and adjusts:
- **Text direction** (RTL/LTR)
- **Font families** (optimized for each language)
- **Message content**

## 📧 Email Configuration

### Gmail Setup (Recommended)

1. **Enable 2-Factor Authentication** on your Google account
2. **Generate App Password**:
   - Go to Google Account Settings
   - Security → 2-Step Verification
   - App Passwords → Generate new password
3. **Update .env file**:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-16-digit-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="Your App Name"
   ```

### Alternative Email Services

#### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-key
```

#### Amazon SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
```

### Email Template Features

- **HTML format** with beautiful design
- **Gmail-optimized** for inbox delivery
- **Emoji support** (🛡️ 🔐) for visual appeal
- **Responsive design** for mobile devices
- **Security notices** and proper branding

## 🔒 Security Features

### Password Protection
- **Bcrypt hashing** for master passwords
- **Session-based** verification tracking
- **Time-limited codes** (10-minute expiry)
- **Secure middleware** protection

### Email Security
- **Anti-spam headers** for better delivery
- **Proper Message-ID** generation
- **Security notices** in all emails
- **Rate limiting** protection

### QR Code Security
- **Local generation** with external fallback
- **Secure secret** generation
- **One-time setup** process
- **Backup manual entry** option

## 🛠️ Customization

### Custom Views

Publish and customize views:

```bash
php artisan vendor:publish --tag=snawbar-guardian-views
```

Then edit files in `resources/views/vendor/snawbar-guardian/`

### Custom Translations

Publish and customize translations:

```bash
php artisan vendor:publish --tag=snawbar-guardian-lang
```

Then edit files in `lang/vendor/snawbar-guardian/`

### Custom Middleware

You can extend or replace the middleware:

```php
// In your Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \Your\Custom\GuardianMiddleware::class,
    ],
];
```

### Custom Email Templates

Override the email template by creating:
`resources/views/vendor/snawbar-guardian/mail/code.blade.php`

## 🧪 Testing

### Manual Testing

1. **Test Email Configuration**:
   ```php
   // In Laravel Tinker
   Mail::raw('Test email', function ($message) {
       $message->to('test@gmail.com')->subject('Test');
   });
   ```

2. **Test Master Password**:
   ```php
   // Check password hash
   Hash::check('your-password', config('guardian.master-password'));
   ```

3. **Test 2FA Flow**:
   - Log in with master password
   - Check email delivery
   - Verify code entry
   - Test Google Authenticator setup

### Unit Testing

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuardianTest extends TestCase
{
    use RefreshDatabase;

    public function test_guardian_middleware_redirects_unverified_users()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertRedirect();
    }

    public function test_email_code_verification()
    {
        $user = User::factory()->create();
        $user->update(['two_factor_code' => '123456']);
        
        $response = $this->actingAs($user)
                        ->post('/guardian/email/verify', ['code' => '123456']);
        
        $response->assertRedirect('/');
    }
}
```

## 🐛 Troubleshooting

### Common Issues

#### Email Not Delivered
**Problem**: Gmail refuses to deliver emails
**Solution**: 
1. Use Gmail App Password (not regular password)
2. Ensure proper SMTP configuration
3. Check spam folder
4. Verify DNS records (SPF, DKIM)

#### QR Code Not Displaying
**Problem**: QR code fails to generate
**Solution**:
1. Install required packages: `simplesoftwareio/simple-qrcode`
2. Check PHP GD extension
3. Verify internet connection for fallback

#### RTL Languages Not Working
**Problem**: Arabic/Kurdish text displays incorrectly
**Solution**:
1. Ensure proper font support
2. Check CSS direction attribute
3. Verify language file encoding (UTF-8)

#### Middleware Not Working
**Problem**: 2FA not triggering
**Solution**:
1. Check middleware registration
2. Verify route configuration
3. Ensure Guardian is enabled in config

### Debug Mode

Enable debugging in config:

```php
// Temporarily add to guardian.php
'debug' => env('GUARDIAN_DEBUG', false),
```

Add to .env:
```env
GUARDIAN_DEBUG=true
```

## 📈 Performance

### Optimization Tips

1. **Cache QR Codes**: Store generated QR codes in cache
2. **Queue Emails**: Use Laravel queues for email sending
3. **Database Indexing**: Add indexes to Guardian columns
4. **Session Storage**: Use Redis for session storage

### Database Indexes

```php
Schema::table('users', function (Blueprint $table) {
    $table->index('google2fa_secret');
    $table->index('google2fa_verified');
    $table->index('two_factor_code');
});
```

## 🤝 Contributing

1. **Fork** the repository
2. **Create** a feature branch
3. **Make** your changes
4. **Add** tests if applicable
5. **Submit** a pull request

### Development Setup

```bash
git clone https://github.com/mikailfaruqali/guardian.git
cd guardian
composer install
```

### Code Style

We follow PSR-12 coding standards:

```bash
composer run-script format
```

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Credits

- **[Snawbar](https://github.com/mikailfaruqali)** - Package author
- **[PragmaRX Google2FA](https://github.com/antonioribeiro/google2fa)** - Google Authenticator implementation
- **[SimpleSoftwareIO QrCode](https://github.com/SimpleSoftwareIO/simple-qrcode)** - QR code generation
- **[Laravel](https://laravel.com)** - The framework that makes it all possible

## 📞 Support

- **GitHub Issues**: [Report bugs or request features](https://github.com/mikailfaruqali/guardian/issues)
- **Email**: alanfaruq85@gmail.com
- **Documentation**: [Full documentation](https://github.com/mikailfaruqali/guardian/wiki)

---

**Guardian** - Protecting your Laravel applications with elegant two-factor authentication. 🛡️