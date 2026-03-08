<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Update</title>
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

        .reason-box {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
            color: #991b1b;
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
            <h1>Application Update</h1>
        </div>
        <div class="body">
            <p>Dear {{ $application->user->first_name }},</p>
            <p>Thank you for your interest in the <strong>{{ $job->title }}</strong> position. After careful
                consideration, we have decided to move forward with other candidates at this time.</p>

            @if($reason)
                <div class="reason-box">
                    <strong>Feedback:</strong><br>
                    {{ $reason }}
                </div>
            @endif

            <p>We appreciate the time you invested in applying and encourage you to apply for future opportunities that
                match your skills.</p>
            <p>Best regards,<br>The Hiring Team</p>
        </div>
        <div class="footer">
            This email was sent from AVAA Recruitment Management System
        </div>
    </div>
</body>

</html>