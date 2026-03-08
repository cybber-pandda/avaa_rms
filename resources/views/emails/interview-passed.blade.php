<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
        }

        .header {
            background: linear-gradient(135deg, #0d6b5e, #10a37f);
            padding: 28px 32px;
        }

        .header h1 {
            color: #fff;
            font-size: 20px;
            margin: 0;
        }

        .body {
            padding: 28px 32px;
            color: #374151;
            font-size: 15px;
            line-height: 1.7;
        }

        .success-box {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
            color: #065f46;
            font-size: 14px;
            line-height: 1.6;
        }

        .footer {
            padding: 20px 32px;
            background: #f9fafb;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Congratulations!</h1>
        </div>
        <div class="body">
            <p>Dear {{ $application->user->first_name }},</p>
            <p>We are thrilled to inform you that you have <strong>passed the interview</strong> for the
                <strong>{{ $job->title }}</strong> position!</p>

            <div class="success-box">
                Congratulations on your successful interview. We were very impressed with your skills and experience.
                The employer will be reaching out to you shortly with further details and next steps.
            </div>

            <p>Welcome aboard! We are excited to have you join the team.</p>
            <p>Best regards,<br>The Hiring Team</p>
        </div>
        <div class="footer">
            This email was sent from AVAA Recruitment Management System
        </div>
    </div>
</body>

</html>