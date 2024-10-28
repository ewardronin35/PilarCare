@component('mail::message')
# New Complaint Alert

Dear {{ $complaint->first_name }} {{ $complaint->last_name }},

We have received your complaint submitted on {{ $complaint->created_at->format('Y-m-d') }}.

**Description of Sickness:** {{ $complaint->sickness_description }}

**Pain Assessment:** {{ $complaint->pain_assessment }}

**Confine Status:** {{ ucwords(str_replace('_', ' ', $complaint->confine_status)) }}

**Medicine Given:** {{ $complaint->medicine_given }}


@if($complaint->report_url)
You can view or download your clinic slip here:

@component('mail::button', ['url' => $complaint->report_url])
View PDF
@endcomponent
@endif

Thank you for reaching out to us.

Best regards,  
Clinic Team
@endcomponent
