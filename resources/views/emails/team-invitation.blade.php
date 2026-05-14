@component('mail::message')
# You've been invited to join {{ $invitation->team->name }}

{{ $invitation->team->name }} is using the **Combined Perception CRM** — an AI-native relationship management platform currently in active development.

@component('mail::button', ['url' => $acceptUrl])
Accept Invitation
@endcomponent

@if($invitation->expires_at)
This invitation expires {{ $invitation->expires_at->diffForHumans() }}.
@endif

---

> **⚠️ Pre-Alpha Notice**
> This platform is currently in a **proof-of-concept / pre-alpha stage** and is being tested internally. You may encounter incomplete features, rough edges, or unexpected behaviour. Your feedback is welcome and valuable as we continue building.

If you weren't expecting this invitation, you can safely ignore this email.

**Combined Perception**
*Building AI-native tools for enterprise teams*
@endcomponent
