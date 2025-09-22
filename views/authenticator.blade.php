<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ku') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('snawbar-guardian::guardian.Guardian') }} - {{ __('snawbar-guardian::guardian.Two-Factor Authentication') }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            direction: {{ app()->isLocale('ku') ? 'rtl' : 'ltr' }};
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            border-radius: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .subtitle {
            color: #718096;
            margin-bottom: 32px;
            font-size: 14px;
        }
        
        .setup-box {
            background: #f7fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            text-align: center;
        }
        
        .setup-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 16px;
        }
        
        .qr-code {
            margin: 16px 0;
        }
        
        .secret-box {
            background: #edf2f7;
            padding: 12px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            margin-top: 12px;
            color: #4a5568;
        }
        
        .form-group {
            margin-bottom: 24px;
            text-align: left;
        }
        
        label {
            display: block;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 18px;
            text-align: center;
            letter-spacing: 4px;
            font-weight: 600;
            transition: all 0.2s;
            background: #f7fafc;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            margin-bottom: 16px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            border-left: 4px solid;
        }
        
        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border-left-color: #e53e3e;
        }
        
        .info-text {
            color: #718096;
            font-size: 13px;
            margin-top: 16px;
            line-height: 1.5;
        }
        
        @media (max-width: 480px) {
            .card {
                margin: 10px;
                padding: 30px 20px;
            }
            .logo {
                width: 56px;
                height: 56px;
                font-size: 24px;
            }
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🛡️</div>
        <h1>{{ __('snawbar-guardian::guardian.Guardian Security') }}</h1>
        <p class="subtitle">{{ __('snawbar-guardian::guardian.Two-Factor Authentication') }}</p>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($isFirstTime)
            <div class="setup-box">
                <div class="setup-title">{{ __('snawbar-guardian::guardian.First-time Setup') }}</div>
                <p style="color: #718096; font-size: 13px; margin-bottom: 16px;">
                    {{ __('snawbar-guardian::guardian.Install Google Authenticator or similar app') }}
                </p>
                
                <div class="qr-code">
                    {!! $qrCode !!}
                </div>
                
                @if(isset($secret) && $secret)
                    <div class="secret-box">
                        <div style="font-size: 11px; margin-bottom: 4px; color: #718096;">
                            {{ __('snawbar-guardian::guardian.Manual Setup (if QR doesn\'t work):') }}
                        </div>
                        {{ $secret }}
                    </div>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('guardian.authenticator.verify') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('snawbar-guardian::guardian.Enter 6-digit code from your authenticator app') }}</label>
                <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus>
            </div>
            
            <button type="submit" class="btn">{{ __('snawbar-guardian::guardian.Verify Code') }}</button>
        </form>
        
        <p class="info-text">{{ __('snawbar-guardian::guardian.Open your Google Authenticator app to get the verification code') }}</p>
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