<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Upcoming Leave Alert</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <img src="{{ asset('images/wg-logo.png') }}" alt="Waltergates Logo" style="max-height: 80px;">
            </td>
        </tr>
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; padding: 30px;">
                    <tr>
                        <td>
                            <h2 style="color: #003366;">Hello {{ $leave->user->name }},</h2>
                            <p style="font-size: 16px; color: #333333;">
                                This is a friendly reminder that your approved leave begins tomorrow ({{ \Carbon\Carbon::parse($leave->start_date)->format('l, d M Y') }}).
                            </p>

                            <table style="margin-top: 20px; font-size: 15px;">
                                <tr>
                                    <td><strong>Leave Type:</strong></td>
                                    <td>{{ $leave->leaveType->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Expected Return:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->addDay()->format('l, d M Y') }}</td>
                                </tr>
                            </table>

                            <p style="margin-top: 20px; font-size: 15px; color: #555;">
                                Please ensure any pending tasks are handed over if necessary.
                            </p>

                            <p style="margin-top: 30px; color: #333;">
                                Warm regards,<br>
                                <strong>HR Department</strong><br>
                                Waltergates Ghana Ltd
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="color: #999; font-size: 12px; margin-top: 20px;">
                    &copy; {{ date('Y') }} Waltergates Ghana Ltd. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
