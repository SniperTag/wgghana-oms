@component('mail::message')
# Appointment Reminder

You have an upcoming appointment scheduled.

**Visitor:** {{ $appointment->visitor_name }}  
**Date:** {{ $appointment->date->format('l, M d, Y') }}  
**Time:** {{ $appointment->date->format('h:i A') }}  
**Meeting Type:** {{ $appointment->meeting_type }}  
@if($appointment->description)
**Description:** {{ $appointment->description }}
@endif

@component('mail::button', ['url' => url('/host/appointments')])
View Appointments
@endcomponent

Thanks,  
**Waltergates Ghana Ltd**
@endcomponent
