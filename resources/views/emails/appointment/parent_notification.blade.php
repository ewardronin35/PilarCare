@component('mail::message')
# Appointment Scheduled

Dear {{ $parent->first_name }} {{ $parent->last_name }},

We are pleased to inform you that **{{ $appointment->patient_name }}** has an appointment scheduled on **{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}** at **{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}** with **Dr. {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}**.

**Appointment Details:**

- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Type:** {{ $appointment->appointment_type }}

If you have any questions or need to reschedule, please contact us at [support@pilarcare.com](mailto:support@pilarcare.com).

Thank you for choosing PilarCare!

@component('mail::button', ['url' => config('app.url')])
Visit Our Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
