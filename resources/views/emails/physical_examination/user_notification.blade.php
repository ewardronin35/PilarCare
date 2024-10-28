@component('mail::message')
# New Physical Examination Recorded

Dear {{ $user->first_name }} {{ $user->last_name }},

A new physical examination has been recorded in your medical records.

**Examination Details:**

- **Height:** {{ $physicalExamination->height }} cm
- **Weight:** {{ $physicalExamination->weight }} kg
- **Vision:** {{ $physicalExamination->vision }}
- **Remarks:** {{ $physicalExamination->remarks ?? 'N/A' }}

@component('mail::button', ['url' => config('app.url')])
View Your Medical Records
@endcomponent

If you have any questions or concerns, please contact us at [support@pilarcare.com](mailto:support@pilarcare.com).

Thanks,<br>
{{ config('app.name') }}
@endcomponent
