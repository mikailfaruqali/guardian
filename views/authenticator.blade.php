<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Guardian') }} - {{ __('Two-Factor Authentication') }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            direction: ltr;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            padding: 0;
            overflow: hidden;
        }
        
        .header {
            text-align: center;
            padding: 40px 30px 30px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .app-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
        }
        
        .app-logo img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
        
        .body {
            padding: 30px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid;
        }
        
        .alert-error {
            background: #fff5f5;
            color: #c53030;
            border-color: #fed7d7;
        }
        
        .qr-section {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .qr-code {
            margin: 15px 0;
        }
        
        .qr-code img {
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .setup-instructions {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
            font-size: 14px;
        }
        
        .setup-instructions h3 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .setup-instructions ol {
            margin: 0;
            padding-left: 20px;
            line-height: 1.5;
        }
        
        .setup-instructions li {
            margin-bottom: 5px;
            color: #555;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 16px;
            text-align: center;
            letter-spacing: 2px;
            font-weight: 500;
            transition: all 0.2s;
            background: #f9fafb;
        }
        
        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            background: white;
        }
        
        .btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        
        .info {
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            margin-top: 15px;
            line-height: 1.4;
        }
        
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                max-width: none;
            }
            
            .header, .body {
                padding: 20px;
            }
            
            .app-logo {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="app-logo">
                🛡️
            </div>
            <h1>{{ __('Guardian Security') }}</h1>
        </div>
        
        <div class="body">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if(isset($isFirstTime) && $isFirstTime)
                <div class="setup-instructions">
                    <h3>{{ __('First-time Setup') }}</h3>
                    <ol>
                        <li>{{ __('Install Google Authenticator or similar app') }}</li>
                        <li>{{ __('Scan the QR code below') }}</li>
                        <li>{{ __('Enter the 6-digit code from the app') }}</li>
                    </ol>
                </div>

                <div class="qr-section">
                    <div class="qr-code">
                        {!! $qrCode !!}
                    </div>
                    @if(isset($secret) && $secret)
                        <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                            <p style="font-size: 12px; color: #6c757d; margin-bottom: 5px;">{{ __('Manual Setup (if QR doesn\'t work):') }}</p>
                            <p style="font-family: monospace; font-size: 14px; word-break: break-all; color: #333;">{{ $secret }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('guardian.authenticator.verify') }}">
                @csrf
                <div class="form-group">
                    <label>{{ __('Enter 6-digit code from your authenticator app') }}</label>
                    <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus>
                </div>
                
                <button type="submit" class="btn btn-primary">{{ __('Verify Code') }}</button>
            </form>
            
            <div class="info">
                <p>{{ __('Open your Google Authenticator app to get the verification code') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Auto submit when 6 digits entered
        document.querySelector('input[name="code"]').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                setTimeout(() => {
                    e.target.closest('form').submit();
                }, 300);
            }
        });
    </script>
</body>
</html>