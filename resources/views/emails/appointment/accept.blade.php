<p>Dear {{ $appointment->visitor_name }},</p>

<p>Your appointment with {{ $appointment->host->name ?? 'our team' }} has been <strong>accepted</strong>.</p>

<p><strong>Date:</strong> {{ $appointment->date->format('l, M d, Y') }}<br>
<strong>Time:</strong> {{ $appointment->date->format('h:i A') }}</p>

<p>We look forward to meeting you.</p>
<p>Thanks,<br>Waltergates Ghana Ltd</p>
