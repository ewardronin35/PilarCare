@component('mail::message')
# New Physical Examination for {{ $studentUser->first_name }} {{ $studentUser->last_name }}

Dear {{ $parentUser->first_name }} {{ $parentUser->last_name }},

A new physical examination has been recorded for your child, {{ $studentUser->first_name }} {{ $studentUser->last_name }}.

**Examination Details:**

- **Height:** {{ $physicalExamination->height }} cm
- **Weight:** {{ $physicalExamination->weight }} kg
- **Vision:** {{ $physicalExamination->vision }}
- **Remarks:** {{ $physicalExamination->remarks ?? 'N/A' }}

@component('mail::button', ['url' => config('app.url')])
View Your Child's Medical Records
@endcomponent

If you have any questions or concerns, please contact us at [support@pilarcare.com](mailto:support@pilarcare.com).

Thanks,<br>
{{ config('app.name') }}
@endcomponent
