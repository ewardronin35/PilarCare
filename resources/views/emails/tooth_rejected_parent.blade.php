@component('mail::message')
# Your Child's Dental Record Rejected

Dear {{ $parentName }},

We regret to inform you that your child, **{{ $userName }}'s** dental record for tooth number **{{ $toothNumber }}** has been **rejected**.

### Details:
- **Patient Name:** {{ $patientName }}
- **Tooth Number:** {{ $toothNumber }}
- **Status:** {{ $status }}
- **Notes:** {{ $notes ?? 'N/A' }}

Please review the details and contact us for further assistance.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
