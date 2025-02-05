@component('mail::message')
# Hello {{ $user->first_name }},

This is a friendly reminder to submit your health examination documents for the current school year.

Please ensure that you provide all the necessary documents to avoid any delays in your records.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
