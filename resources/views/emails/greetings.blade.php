<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{ $companyName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>Welcome to {{ $companyName }}!</h1>
        </div>
        <p>Dear {{ $name }},</p>
        <p>Thank you for signing up with <strong>{{ $companyName }}</strong>, your trusted partner for reliable vehicle rental services.</p>
        <p>We're thrilled to have you on board! Whether you're planning a road trip, need a temporary vehicle for work, or require a reliable car for everyday use, we've got you covered.</p>
        <p>As part of our family, you can expect:</p>
        <ul>
            <li>A wide selection of well-maintained vehicles to choose from.</li>
            <li>Flexible rental plans tailored to your needs.</li>
            <li>Exceptional customer service available at every step of your journey.</li>
        </ul>
        <p>Get ready to explore the road with ease and confidence. Should you need any assistance or have inquiries, don't hesitate to reach out to us at [support email or phone number].</p>
        <p>We look forward to serving you!</p>
        <p>Warm regards,</p>
        <p><strong>{{ $companyName }} Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
        <p><a href="[Company Website]" target="_blank">Visit our website</a></p>
    </div>
</body>
</html>
