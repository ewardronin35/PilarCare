@component('mail::message')
# Child's Medical Record Approved

Dear {{ $parentUser->first_name }},

We are pleased to inform you that your child, **{{ $student->first_name }} {{ $student->last_name }}**,'s medical record submitted on **{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('F d, Y') }}** has been **approved**.


Thank you for keeping your child's medical records up-to-date.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
