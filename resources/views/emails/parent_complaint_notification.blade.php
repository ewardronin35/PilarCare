@component('mail::message')
# New Complaint Submitted by Your Child

Dear Parent,

We would like to inform you that your child, **{{ $studentName }}**, has submitted a new complaint.

**Details of the Complaint:**

- **Description of Sickness:** {{ $sicknessDescription }}
- **Pain Assessment:** {{ $painAssessment }}/10
- **Medicine Given:** {{ $medicineGiven }}

@if($complaint->report_url)
        <p>You can view or download your clinic slip here:</p>
        <p><a href="{{ $complaint->report_url }}" target="_blank">View PDF</a></p>
    @endif

    <p>Thank you for reaching out to us.</p>

    <p>Best regards,<br>Clinic Team</p>

Thank you for your attention.

Regards,<br>
{{ config('app.name') }}
@endcomponent
