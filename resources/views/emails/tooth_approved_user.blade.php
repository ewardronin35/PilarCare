@component('mail::message')
# Dental Record Approved

Dear {{ $userName }},

We are pleased to inform you that your dental record for tooth number **{{ $toothNumber }}** has been **approved**.

### Details:
- **Patient Name:** {{ $patientName }}
- **Tooth Number:** {{ $toothNumber }}
- **Status:** {{ $status }}
- **Notes:** {{ $notes ?? 'N/A' }}


If you have any questions, feel free to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
