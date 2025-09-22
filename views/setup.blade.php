<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guardian - Setup Authenticator</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        .body {
            padding: 30px;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .step {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .step h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .step p {
            color: #666;
            margin-bottom: 15px;
        }
        .qr-container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            margin: 15px 0;
        }
        .app-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .app-link {
            flex: 1;
            min-width: 120px;
            padding: 10px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
            transition: all 0.3s;
        }
        .app-link:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        .secret-box {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
            margin: 10px 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
            letter-spacing: 2px;
            font-weight: 600;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102,126,234,0.3);
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Guardian Setup</h1>
            <p>Configure Google Authenticator</p>
        </div>
        
        <div class="body">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="step">
                <h3>📱 Step 1: Download App</h3>
                <p>Install Google Authenticator on your phone:</p>
                <div class="app-links">
                    <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank" class="app-link">
                        📱 iOS App Store
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="app-link">
                        🤖 Google Play
                    </a>
                </div>
            </div>

            <div class="step">
                <h3>📷 Step 2: Scan QR Code</h3>
                <p>Open the app and scan this code:</p>
                <div class="qr-container">
                    {!! $qrCode !!}
                </div>
            </div>

            <div class="step">
                <h3>⌨️ Step 3: Manual Entry (Optional)</h3>
                <p>If you can't scan, enter this secret manually:</p>
                <div class="secret-box">{{ $secret }}</div>
            </div>

            <div class="step">
                <h3>✅ Step 4: Verify Setup</h3>
                <p>Enter the 6-digit code from your app:</p>
                
                <form method="POST" action="{{ route('guardian.setup.complete') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus>
                    </div>
                    <button type="submit" class="btn">Complete Setup</button>
                </form>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> Keep your phone safe! You'll need it to login from now on.
            </div>
        </div>
    </div>

    <script>
        // Auto submit when 6 digits entered
        document.querySelector('input[name="code"]').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                setTimeout(() => {
                    e.target.closest('form').submit();
                }, 500);
            }
        });
    </script>
</body>
</html>