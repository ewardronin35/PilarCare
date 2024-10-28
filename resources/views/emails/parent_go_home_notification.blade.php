@component('mail::message')
# Student Going Home Notification

Dear {{ $student->parents->first()->user->first_name ?? 'Parent' }},

We would like to inform you that your child, **{{ $student->first_name }} {{ $student->last_name }}**, has been marked as requiring to go home.

**Details:**

- **Complaint Date:** {{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}
- **Description:** {{ $complaint->sickness_description }}
- **Medicine Given:** {{ $complaint->medicine_given }}
- **Confine Status:** {{ ucfirst($complaint->confine_status) }}
- **Go Home Status:** {{ ucfirst($complaint->go_home) }}

Please make the necessary arrangements to ensure your child's well-being.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
