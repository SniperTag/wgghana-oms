<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>Welcome to the <strong>{{ config('app.name') }}</strong> platform.</p>

    <p><strong>Your Staff ID is:</strong> {{ $staff_id }}</p>
    <p><strong>Your Clock-In PIN is:</strong> {{ $clockin_pin }}</p>
    <P><Strong>Your Temporal Password</Strong>{{ $password }}</P>

    <p>To get started, please set your password by clicking the button below:</p>

    <p>
        <a href="{{ $resetUrl }}"
           style="background-color: #3490dc; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;">
            Set Your Password
        </a>
    </p>

    <p>If the button above doesn’t work, copy and paste this link into your browser:</p>
    <p>{{ $resetUrl }}</p>

    <br>

    <p>Thanks,<br>The {{ config('app.name') }} Team</p>
</body>
</html>
