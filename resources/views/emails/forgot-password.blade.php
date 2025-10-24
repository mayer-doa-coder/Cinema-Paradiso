<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f4f4f4;
            padding: 30px;
            border-radius: 10px;
        }
        .header {
            background-color: #dd003f;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .password-box {
            background-color: #f9f9f9;
            border: 2px solid #dd003f;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #dd003f;
            border-radius: 5px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cinema Paradiso</h1>
            <p>Password Reset Request</p>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            <p>We received a request to reset your password. Here is your temporary password:</p>
            
            <div class="password-box">
                {{ $tempPassword }}
            </div>
            
            <div class="warning">
                <strong>⚠️ Important Security Notice:</strong>
                <ul>
                    <li>This is a temporary password valid for one-time use</li>
                    <li>Please change it immediately after logging in</li>
                    <li>Do not share this password with anyone</li>
                    <li>If you didn't request this, please contact us immediately</li>
                </ul>
            </div>
            
            <p><strong>How to use your temporary password:</strong></p>
            <ol>
                <li>Go to Cinema Paradiso login page</li>
                <li>Enter your email or username</li>
                <li>Use the temporary password above</li>
                <li>Update your password in account settings</li>
            </ol>
            
            <p>If you did not request a password reset, please ignore this email or contact our support team if you have concerns.</p>
            
            <p>Best regards,<br>
            The Cinema Paradiso Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Cinema Paradiso. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
