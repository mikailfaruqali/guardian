<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ku') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('snawbar-guardian::guardian.title') }} - {{ __('snawbar-guardian::guardian.email_verify') }}</title>
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
        
        .btn-secondary {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(203, 213, 224, 0.3);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left-color: #38a169;
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
        <div class="logo">🔐</div>
        <h1>{{ __('snawbar-guardian::guardian.security') }}</h1>
        <p class="subtitle">{{ __('snawbar-guardian::guardian.email_verify') }}</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('guardian.email.verify') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('snawbar-guardian::guardian.enter_email_code') }}</label>
                <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus>
            </div>
            
            <button type="submit" class="btn">{{ __('snawbar-guardian::guardian.verify') }}</button>
        </form>
        
        <form method="POST" action="{{ route('guardian.email.send') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">{{ __('snawbar-guardian::guardian.resend') }}</button>
        </form>
        
        <p class="info-text">{{ __('snawbar-guardian::guardian.code_sent') }}</p>
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
