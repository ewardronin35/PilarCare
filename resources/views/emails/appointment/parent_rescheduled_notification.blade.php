@component('mail::message')
# Appointment Rescheduled

Dear {{ $parent->first_name }} {{ $parent->last_name }},

We would like to inform you that **{{ $appointment->patient_name }}**'s appointment has been rescheduled.

**New Appointment Details:**

- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Doctor:** Dr. {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}
- **Type:** {{ $appointment->appointment_type }}

If you have any questions or need further assistance, please contact us at [support@pilarcare.com](mailto:support@pilarcare.com).

Thank you for your understanding.

@component('mail::button', ['url' => config('app.url')])
Visit Our Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
