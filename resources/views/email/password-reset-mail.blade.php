@component('mail::message')
# Password Reset

We received a request to reset the password for <a>{{ $details->email }}</a>. 
Your new password will apply to all our services. 

@component('mail::panel')
Click the link below to proceed.
@endcomponent

@component('mail::button', ['url' => route('index')])
Reset Password
@endcomponent

Thanks,
<strong>
    {{ config('app.name') }}
</strong>
@endcomponent
