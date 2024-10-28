@component('mail::message')
# Medical Record Rejected

Dear {{ $medicalRecord->name }},

We regret to inform you that your medical record submitted on **{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('F d, Y') }}** has been **rejected**.

Please review your submission and ensure all information is accurate and complete.

If you have any questions, feel free to contact us.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
