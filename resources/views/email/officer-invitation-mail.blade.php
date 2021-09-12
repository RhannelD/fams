@component('mail::message')
# Scholarship Officer Invitation

You have been invited to become an officer of <strong>{{ $details->scholarship }}</strong>.

@component('mail::panel')
Open the link to respond.
@endcomponent

@component('mail::button', ['url' => route('invite', [$details->token])])
Open
@endcomponent

Thanks,
<strong>
    {{ config('app.name') }}
</strong>
@endcomponent
