@component('mail::message')
# Financial Assistance Management System

<strong>
    {{ $details->poster }}
</strong>
posted a new annoucement in 
<strong>
    {{ $details->scholarship }}
</strong>.

@component('mail::panel')
Click the link to view.
@endcomponent

@component('mail::button', ['url' => $details->url])
Open
@endcomponent

Thanks, {{ config('app.name') }}
@endcomponent
