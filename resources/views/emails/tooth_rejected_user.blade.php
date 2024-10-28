@component('mail::message')
# Dental Record Rejected

Dear {{ $userName }},

We regret to inform you that your dental record for tooth number **{{ $toothNumber }}** has been **rejected**.

### Details:
- **Patient Name:** {{ $patientName }}
- **Tooth Number:** {{ $toothNumber }}
- **Status:** {{ $status }}
- **Notes:** {{ $notes ?? 'N/A' }}

Please review the details and contact us for further assistance.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
