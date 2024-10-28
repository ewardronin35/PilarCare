@component('mail::message')
# Appointment Pending Approval

Dear {{ $appointment->patient_name }},

Your appointment has been scheduled and is currently pending approval.

**Appointment Details:**

- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Doctor:** Dr. {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}
- **Type:** {{ $appointment->appointment_type }}

Once your appointment is confirmed, you will receive another notification.

If you have any questions or need further assistance, please contact us at [support@pilarcare.com](mailto:support@pilarcare.com).

@component('mail::button', ['url' => config('app.url')])
Visit Our Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
