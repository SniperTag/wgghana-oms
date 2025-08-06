@component('mail::message')
# Appointment Rescheduled

The following appointment has been rescheduled by the host:

**Visitor:** {{ $appointment->visitor_name }}  
**Old Date & Time:** {{ $oldDateTime->format('M d, Y h:i A') }}  
**New Date & Time:** {{ $appointment->date->format('M d, Y h:i A') }}  
**Meeting Type:** {{ ucfirst($appointment->meeting_type) }}

Please review if necessary.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
