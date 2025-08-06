<p>Dear {{ $appointment->visitor_name }},</p>

<p>Unfortunately, your appointment request with {{ $appointment->host->name ?? 'our team' }} was <strong>declined</strong>.</p>

<p><strong>Reason:</strong> {{ $appointment->decline_reason }}</p>

<p>You may request another appointment or contact us for more details.</p>
<p>Thanks,<br>Waltergates Ghana Ltd</p>
