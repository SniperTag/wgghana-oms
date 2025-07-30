<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Welcome to Waltergates Office System</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background-color: #f4faff;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); overflow: hidden;">

                    <!-- Header / Logo -->
                    <tr>
                        <td align="center" style="background-color: #0ea5e9; padding: 20px;">
                            <img src="{{ asset('build/assets/image/Office_logo.jpg') }}" alt="Waltergates Logo" width="120" style="margin-bottom: 10px;">
                            <h2 style="color: white; margin: 0;">Welcome to Waltergates Office System</h2>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <h3 style="color: #0ea5e9;">Hello {{ strtoupper($user->name) }},</h3>
                            <p style="font-size: 16px; color: #333;">
                                We are excited to have you on board! Your account has been successfully created.
                            </p>

                            <table cellpadding="8" cellspacing="0" width="100%" style="margin: 20px 0; font-size: 15px;">
                                <tr>
                                    <td><strong>Staff ID:</strong></td>
                                    <td>{{ $user->staff_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Temporary Password:</strong></td>
                                    <td>{{ $password }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Default Clock-In PIN:</strong></td>
                                    <td>{{ $clockinPin }}</td>
                                </tr>
                            </table>

                            <p style="font-size: 15px; color: #333;">
                                Please reset your password using the link below to secure your account:
                            </p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetUrl }}" style="
                                    background-color: #dc2626;
                                    color: #fff;
                                    padding: 12px 24px;
                                    text-decoration: none;
                                    border-radius: 6px;
                                    font-weight: bold;
                                    display: inline-block;
                                ">Login Now!</a>
                            </p>

                            <p style="font-size: 14px; color: #555;">
                                If you have any questions or need assistance, feel free to reach out to HR.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f5f9; padding: 15px; text-align: center; font-size: 13px; color: #666;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
