<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Update</title>
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
            <h1>Interview Update</h1>
        </div>
        <div class="body">
            <p>Dear {{ $application->user->first_name }},</p>
            <p>Thank you for taking the time to interview for the <strong>{{ $job->title }}</strong> position.</p>

            <p>While we appreciated the opportunity to meet with you and learn about your background, we have decided to
                move forward with other candidates whose qualifications more closely match our current needs for this
                role.</p>

            <p>We thank you for your interest and wish you the best of luck in your future endeavors.</p>
            <p>Best regards,<br>The Hiring Team</p>
        </div>
        <div class="footer">
            This email was sent from AVAA Recruitment Management System
        </div>
    </div>
</body>

</html>