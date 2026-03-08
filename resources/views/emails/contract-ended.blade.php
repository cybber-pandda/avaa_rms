<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Update</title>
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
            <h1>Contract Update</h1>
        </div>
        <div class="body">
            <p>Dear {{ $application->user->first_name }},</p>
            <p>We are writing to inform you that your contract for the role of <strong>{{ $job->title }}</strong> has
                been ended by the employer.</p>

            <p>We want to thank you for your contributions during your time with us. The employer will process any final
                details regarding your employment separately.</p>

            <p>We wish you the very best in your future career path.</p>
            <p>Best regards,<br>The Hiring Team</p>
        </div>
        <div class="footer">
            This email was sent from AVAA Recruitment Management System
        </div>
    </div>
</body>

</html>