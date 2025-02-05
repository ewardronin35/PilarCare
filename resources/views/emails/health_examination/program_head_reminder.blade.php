@component('mail::message')
# Hello {{ $programHead->first_name }},

This is a reminder that student {{ $student->user->first_name }} {{ $student->user->last_name }} (ID: {{ $student->id_number }}) has not submitted their health examination documents for the current school year.

Please follow up with the student to ensure timely submission.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
