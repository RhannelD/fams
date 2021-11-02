@component('mail::message')
# Password Reset

We received a request to reset the password for <a>{{ $details->email }}</a>. 
Your new password will apply to all our services. 

@component('mail::panel')
Your confirmation code is <strong>{{ $details->token }}</strong>
@endcomponent

Thanks,
<strong>
    {{ config('app.name') }}
</strong>
@endcomponent
