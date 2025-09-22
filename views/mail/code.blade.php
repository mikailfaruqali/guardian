<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('guardian.ui.app_name', 'Guardian') }} Security Code</title>
    <style>
        body {
            font-family: {{ config('guardian.ui.font_family', "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif") }};
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, {{ config('guardian.ui.primary_color', '#dc3545') }} 0%, {{ config('guardian.ui.secondary_color', '#c82333') }} 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .app-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .app-logo img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .body {
            padding: 40px 30px;
            text-align: center;
        }

        .code-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 3px solid {{ config('guardian.ui.primary_color', '#dc3545') }};
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            display: inline-block;
        }

        .code {
            font-size: 48px;
            font-weight: 900;
            color: {{ config('guardian.ui.primary_color', '#dc3545') }};
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }

        .message {
            font-size: 18px;
            color: #333;
            margin: 20px 0;
            line-height: 1.6;
        }

        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        .security-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 0 10px;
            }
            .header, .body, .footer {
                padding: 20px;
            }
            .code {
                font-size: 36px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="app-logo">
                @if(config('guardian.ui.app_logo') && file_exists(public_path(config('guardian.ui.app_logo'))))
                    <img src="{{ asset(config('guardian.ui.app_logo')) }}" alt="{{ config('guardian.ui.app_name', 'Guardian') }}">
                @else
                    🛡️
                @endif
            </div>
            <h1>{{ config('guardian.ui.app_name', 'Guardian') }} Security</h1>
            <p>Your Two-Factor Authentication Code</p>
        </div>

        <div class="body">
            <div class="security-icon">🔐</div>
            
            <p class="message">
                Hello! You have requested a two-factor authentication code for {{ config('guardian.ui.app_name', 'Guardian') }}.
            </p>

            <div class="code-container">
                <div class="code">{{ $code }}</div>
            </div>

            <p class="message">
                Please enter this code on the verification page to complete your login.
                <strong>This code will expire in 10 minutes.</strong>
            </p>

            <div class="warning">
                <strong>Security Notice:</strong> If you did not request this code, please ignore this email or contact your administrator immediately.
            </div>
        </div>

        <div class="footer">
            <p>This is an automated message from {{ config('guardian.ui.app_name', 'Guardian') }} Security System.</p>
            <p>&copy; {{ date('Y') }} {{ config('guardian.ui.app_name', 'Guardian') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            border: 3px dashed #667eea;
        }

        .code {
            font-size: 36px;
            font-weight: 800;
            color: #667eea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 0;
        }

        .code-label {
            color: #6c757d;
            font-size: 14px;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .message {
            color: #495057;
            font-size: 16px;
            line-height: 1.6;
            margin: 20px 0;
        }

        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            color: #856404;
        }

        .warning h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }

        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }

        .security-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }

            .header,
            .body,
            .footer {
                padding: 20px;
            }

            .code {
                font-size: 28px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="security-icon">🔐</div>
            <h1>Guardian Security</h1>
            <p>Two-Factor Authentication Code</p>
        </div>

        <div class="body">
            <p class="message">
                You have requested to verify your identity as a master user.
                Please use the following security code to complete your login:
            </p>

            <div class="code-container">
                <div class="code">{{ $code }}</div>
                <div class="code-label">Security Code</div>
            </div>

            <div class="warning">
                <h3>⚠️ Security Notice</h3>
                <ul style="text-align: left; margin: 10px 0;">
                    <li>This code expires in 10 minutes</li>
                    <li>Never share this code with anyone</li>
                    <li>Guardian team will never ask for this code</li>
                    <li>If you didn't request this, please contact support</li>
                </ul>
            </div>

            <p class="message">
                This code was generated automatically by Guardian Security system
                for master user authentication.
            </p>
        </div>

        <div class="footer">
            <p>
                <strong>Guardian Security System</strong><br>
                This email was sent automatically. Please do not reply to this email.
            </p>
            <p style="margin-top: 15px; color: #adb5bd; font-size: 12px;">
                Generated at {{ date('Y-m-d H:i:s') }} UTC
            </p>
        </div>
    </div>
</body>

</html>
