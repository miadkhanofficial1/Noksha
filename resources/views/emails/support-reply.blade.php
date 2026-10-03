<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response to Support Inquiry - Noksha</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #030712;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #F3F4F6;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #0F172A;
            border: 1px solid #1E293B;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .header {
            background: linear-gradient(135deg, #4338CA 0%, #6366F1 100%);
            padding: 32px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #FFFFFF;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #E0E7FF;
            opacity: 0.9;
        }
        .content {
            padding: 32px 30px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #F8FAFC;
            margin-bottom: 16px;
        }
        .reply-box {
            background-color: #1E293B;
            border-left: 4px solid #6366F1;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.7;
            color: #F1F5F9;
            white-space: pre-wrap;
        }
        .inquiry-meta {
            background-color: #0B0F19;
            border: 1px solid #1E293B;
            border-radius: 10px;
            padding: 16px 20px;
            margin-top: 24px;
            font-size: 12px;
            color: #94A3B8;
        }
        .inquiry-meta strong {
            color: #CBD5E1;
        }
        .original-msg {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #1E293B;
            font-style: italic;
            color: #64748B;
            white-space: pre-wrap;
            max-height: 120px;
            overflow: hidden;
        }
        .btn-cta {
            display: inline-block;
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 13px;
            margin-top: 24px;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 24px;
            font-size: 11px;
            color: #64748B;
        }
        .footer a {
            color: #818CF8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <h1>Noksha Support</h1>
                <p>Official Response to Customer Inquiry</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    Hello {{ $inquiry->name }},
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #CBD5E1; margin: 0 0 16px;">
                    Thank you for contacting the Noksha Customer Support Team. Our administrative team has reviewed your inquiry regarding <strong>"{{ $inquiry->subject }}"</strong>. Here is our official response:
                </p>

                <!-- Official Admin Reply Message -->
                <div class="reply-box">
{{ $replyMessage }}
                </div>

                <p style="font-size: 13px; line-height: 1.6; color: #94A3B8; margin: 16px 0;">
                    If you have further questions or require additional assistance, please reply directly to this email or visit your account dashboard.
                </p>

                <div style="text-align: center;">
                    <a href="{{ route('home') }}" class="btn-cta">Visit Noksha Marketplace</a>
                </div>

                <!-- Reference to Original Inquiry -->
                <div class="inquiry-meta">
                    <div><strong>Ticket Reference:</strong> #INQ-{{ str_pad($inquiry->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div><strong>Date Received:</strong> {{ $inquiry->created_at->format('M d, Y • h:i A') }}</div>
                    <div><strong>Subject:</strong> {{ $inquiry->subject }}</div>
                    <div class="original-msg">
                        <strong>Your Original Message:</strong><br>
                        "{{ Str::limit($inquiry->message, 250) }}"
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px;">
                &copy; {{ date('Y') }} Noksha Marketplace (noksha.com). All rights reserved.
            </p>
            <p style="margin: 0;">
                Empowering Bangladeshi visual artists and digital designers.
            </p>
        </div>
    </div>
</body>
</html>
