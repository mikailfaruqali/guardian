<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardian Security Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="font-size: 48px; margin-bottom: 10px;">🛡️</div>
        <h1 style="margin: 0; font-size: 24px; color: #333;">Guardian Security</h1>
        <p style="margin: 5px 0 0 0; font-size: 16px; color: #666;">Your Two-Factor Authentication Code</p>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <div style="font-size: 40px; margin-bottom: 20px;">🔐</div>
        <p style="font-size: 16px; margin-bottom: 20px;">Hello! You have requested a two-factor authentication code for Guardian.</p>
        
        <div style="background-color: #f8f9fa; border: 2px solid #007bff; border-radius: 8px; padding: 20px; margin: 20px 0; display: inline-block;">
            <span style="font-size: 32px; font-weight: bold; color: #007bff; letter-spacing: 3px; font-family: monospace;">{{ $code }}</span>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">Please enter this code on the verification page to complete your login. <strong>This code will expire in 10 minutes.</strong></p>
    </div>

    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 20px 0;">
        <p style="margin: 0; font-size: 14px; color: #856404;">
            <strong>Security Notice:</strong> If you did not request this code, please ignore this email or contact your administrator immediately.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #666;">
        <p style="margin: 0;">This is an automated message from Guardian Security System.</p>
        <p style="margin: 5px 0 0 0;">© {{ date('Y') }} Guardian. All rights reserved.</p>
    </div>

</body>
</html>
