@component('mail::message')
# Scholarship Scholar Invitation

You have been invited to become an scholar of <strong>{{ $details->scholarship }}</strong>.

@component('mail::panel')
Open the link to respond.
@endcomponent

@component('mail::button', ['url' => route('index')])
Open
@endcomponent

Thanks,
<strong>
    {{ config('app.name') }}
</strong>
@endcomponent
