@component('mail::message')
# New Appointment Scheduled

Dear Dr. {{ $doctor->user->first_name }} {{ $doctor->user->last_name }},

You have a new appointment scheduled by **{{ $creator->first_name }} {{ $creator->last_name }}**.

**Details:**
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Type:** {{ $appointment->appointment_type }}

@component('mail::button', ['url' => url('/appointments')])
View Appointments
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
