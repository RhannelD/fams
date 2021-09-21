@component('mail::message')
# Update Email Verification Code

@component('mail::panel')
Your validation code is: 
<strong>
    {{ $details->code }}
</strong>
@endcomponent

Thanks,
<strong>
    {{ config('app.name') }}
</strong>
@endcomponent
