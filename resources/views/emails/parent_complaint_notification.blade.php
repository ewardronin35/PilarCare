@component('mail::message')
# New Complaint Submitted by Your Child

Dear Parent,

We would like to inform you that your child, **{{ $studentName }}**, has submitted a new complaint.

**Details of the Complaint:**

- **Description of Sickness:** {{ $sicknessDescription }}
- **Pain Assessment:** {{ $painAssessment }}/10
- **Medicine Given:** {{ $medicineGiven }}

@component('mail::button', ['url' => url('/complaints')])
View Complaints
@endcomponent

Thank you for your attention.

Regards,<br>
{{ config('app.name') }}
@endcomponent
