<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .header-title {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header-subtitle {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #666;
        }

        .content {
            text-align: center;
            margin-bottom: 30px;
        }

        .content-icon {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .content-text {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .code-box {
            background-color: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            display: inline-block;
        }

        .code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 3px;
            font-family: monospace;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }

        .footer-text {
            margin: 0;
        }

        .footer-copyright {
            margin: 5px 0 0 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-icon">🛡️</div>
        <h1 class="header-title">{{ $subject }}</h1>
        <p class="header-subtitle">Your Two-Factor Authentication Code</p>
    </div>
    <div class="content">
        <div class="content-icon">🔐</div>
        <p class="content-text">Hello <strong>{{ $user->name }}</strong> ({{ $user->email }})! You have requested a
            two-factor authentication code</p>
        <div class="code-box">
            <span class="code">{{ $code }}</span>
        </div>
        <p class="content-text">
            Please enter this code on the verification page to complete your login.
            <strong>
                This code will expire in {{ config('snawbar-guardian.expiration-minutes') }}
                {{ Str::plural('minute', config('snawbar-guardian.expiration-minutes')) }}.
            </strong>
        </p>
    </div>
    <div class="footer">
        <p class="footer-text">This is an automated message from {{ $appName }} System.</p>
        <p class="footer-copyright">© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
    </div>
</body>

</html>
