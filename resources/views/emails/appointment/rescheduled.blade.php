@component('mail::message')
# Appointment Rescheduled

Dear {{ $appointment->patient_name }},

Your appointment with **Dr. {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}** has been rescheduled.

**New Details:**
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Type:** {{ $appointment->appointment_type }}

@component('mail::button', ['url' => url('/appointments')])
View Appointments
@endcomponent

If you have any questions or need to make further changes, please contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
