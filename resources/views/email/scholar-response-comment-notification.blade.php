@component('mail::message')
# Requirement Response Comment

{{ $details->body_message }}

@component('mail::panel')
<strong>{{ $details->commenter }}:</strong> <br>
{{ $details->comment }}
@endcomponent

@component('mail::button', ['url' => $details->url])
Reply
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
