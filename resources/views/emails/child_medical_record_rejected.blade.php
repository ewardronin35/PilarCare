@component('mail::message')
# Child's Medical Record Rejected

Dear {{ $parentUser->first_name }},

We regret to inform you that your child, **{{ $student->first_name }} {{ $student->last_name }}**,'s medical record submitted on **{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('F d, Y') }}** has been **rejected** and deleted.

Please review your child's submission and ensure all information is accurate and complete.

If you have any questions, feel free to contact us.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
