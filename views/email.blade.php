<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Guardian') }} - {{ __('Email Verification') }}</title>
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
        
        .alert-success {
            background: #f0f9ff;
            color: #047857;
            border-color: #a7f3d0;
        }
        
        .alert-error {
            background: #fff5f5;
            color: #c53030;
            border-color: #fed7d7;
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
            margin-bottom: 10px;
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
        
        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        
        .btn-secondary:hover {
            background: #e9ecef;
            border-color: #adb5bd;
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
                🔐
            </div>
            <h1>{{ __('Guardian Security') }}</h1>
        </div>
        
        <div class="body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('guardian.email.verify') }}">
                @csrf
                <div class="form-group">
                    <label>{{ __('Enter the 6-digit code from your email') }}</label>
                    <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus>
                </div>
                
                <button type="submit" class="btn btn-primary">{{ __('Verify Code') }}</button>
            </form>
            
            <form method="POST" action="{{ route('guardian.email.send') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">{{ __('Resend Code') }}</button>
            </form>
            
            <div class="info">
                <p>{{ __('Verification code has been sent to authorized email addresses') }}</p>
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