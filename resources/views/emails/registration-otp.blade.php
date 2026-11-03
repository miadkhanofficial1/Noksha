<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noksha Registration Verification Code</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #030712;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #F3F4F6;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #0F172A;
            border: 1px solid #1E293B;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }
        .header {
            background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #0F172A 100%);
            border-bottom: 1px solid #1E293B;
            padding: 32px 30px;
            text-align: center;
        }
        .shield-badge {
            display: inline-block;
            background: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(6, 182, 212, 0.4);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #38BDF8;
            margin-bottom: 14px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #FFFFFF;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #94A3B8;
        }
        .content {
            padding: 32px 30px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #F8FAFC;
            margin-bottom: 14px;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #94A3B8;
            margin-bottom: 24px;
        }
        .otp-container {
            text-align: center;
            margin: 28px 0;
        }
        .otp-box {
            display: inline-block;
            background: #030712;
            border: 2px solid #06B6D4;
            border-radius: 14px;
            padding: 18px 32px;
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.2);
        }
        .otp-code {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #38BDF8;
            margin-left: 10px;
            display: block;
        }
        .otp-expiry {
            margin-top: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #E2E8F0;
        }
        .expiry-badge {
            color: #F59E0B;
            font-weight: 700;
        }
        .security-notice {
            background-color: #1E293B;
            border-left: 4px solid #6366F1;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 26px 0;
        }
        .security-notice p {
            margin: 0;
            font-size: 12px;
            line-height: 1.6;
            color: #CBD5E1;
        }
        .security-notice strong {
            color: #FFFFFF;
        }
        .footer {
            border-top: 1px solid #1E293B;
            padding: 24px 30px;
            text-align: center;
            background-color: #090D16;
        }
        .footer p {
            margin: 0 0 6px;
            font-size: 11px;
            color: #64748B;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <div class="shield-badge">Noksha Security System</div>
                <h1>Account Registration Verification</h1>
                <p>One-Time Password (OTP) to Activate Your Account</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">Hello, {{ $recipientName }}</div>
                <div class="intro-text">
                    Welcome to Noksha! To complete your registration and activate your creator & buyer account, please enter the 6-digit verification code below:
                </div>

                <!-- OTP Display -->
                <div class="otp-container">
                    <div class="otp-box">
                        <span class="otp-code">{{ $otp }}</span>
                    </div>
                    <div class="otp-expiry">
                        This verification code will expire in <span class="expiry-badge">{{ $expiresInMinutes }} minutes</span>.
                    </div>
                </div>

                <!-- Security Guard Box -->
                <div class="security-notice">
                    <p>
                        <strong>Security Safeguard:</strong> Never share this code with anyone. Noksha will never ask for your verification code. If you did not request this registration, please disregard this email.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} Noksha Creative Marketplace &bull; Security Operations</p>
                <p>This is an automated security transmission. Please do not reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
