@component('mail::message')
# Your Child's Dental Record Approved

Dear {{ $parentName }},

We are pleased to inform you that your child, **{{ $userName }}'s** dental record for tooth number **{{ $toothNumber }}** has been **approved**.

### Details:
- **Patient Name:** {{ $patientName }}
- **Tooth Number:** {{ $toothNumber }}
- **Status:** {{ $status }}
- **Notes:** {{ $notes ?? 'N/A' }}

@component('mail::button', ['url' => url('/dashboard')])
View Your Child's Records
@endcomponent

If you have any questions, feel free to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
