@component('mail::message')
# Medical Record Approved

Dear {{ $medicalRecord->name }},

Your medical record submitted on **{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('F d, Y') }}** has been **approved**.


Thank you for keeping your medical records up-to-date.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
