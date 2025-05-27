@component('mail::message')
# You're Invited!

Click below to register your account:

@component('mail::button', ['url' => route('register.form', ['token' => $token])])
Accept Invitation
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent