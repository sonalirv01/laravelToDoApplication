@component('mail::message')
# Welcome to our platform, {{ $name }}!

You have been registered as an {{$roles}} on our platform.

Thank you for joining us.

Regards,<br>
{{ config('app.name') }}
@endcomponent
