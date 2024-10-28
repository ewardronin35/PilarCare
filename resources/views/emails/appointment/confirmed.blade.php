@component('mail::message')
# Appointment Confirmed

Dear {{ $appointment->patient_name }},

Your appointment with **Dr. {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}** has been confirmed.

**Details:**
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Type:** {{ $appointment->appointment_type }}

@component('mail::button', ['url' => url('/appointments')])
View Appointments
@endcomponent

Thank you for choosing our services!

Best regards,<br>
{{ config('app.name') }}
@endcomponent
