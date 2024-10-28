@component('mail::message')
# Medicine Intake Reminder

Dear {{ $user->first_name }} {{ $user->last_name }},

This is a friendly reminder to take your medicine as per your schedule.

**Medicine Name:** {{ $medicineIntake->medicine_name }}

**Dosage:** {{ $medicineIntake->dosage }}

**Intake Time:** {{ \Carbon\Carbon::parse($medicineIntake->intake_time)->format('h:i A, d M Y') }}

@if($medicineIntake->notes)
**Notes:** {{ $medicineIntake->notes }}
@endif

Please ensure you take your medicine on time.

Thank you.

Best regards,  
{{ config('app.name') }}
@endcomponent
