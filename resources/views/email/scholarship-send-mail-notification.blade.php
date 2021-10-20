@component('mail::message')
# {{ $details->scholarship }}

@component('mail::panel')
From: {{ $details->sender }}
@endcomponent

{{ $details->message }}

@endcomponent
