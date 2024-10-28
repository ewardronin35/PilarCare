@component('mail::message')
# New Dental Examination Recorded

Dear {{ $user->first_name }},

A new dental examination has been recorded in your medical records.

**Date of Examination:** {{ \Carbon\Carbon::parse($dentalExamination->date_of_examination)->format('F j, Y') }}
**Grade & Section:** {{ $dentalExamination->grade_section }}
**Age:** {{ $dentalExamination->age }}
**Birthdate:** {{ \Carbon\Carbon::parse($dentalExamination->birthdate)->format('F j, Y') }}

---

**Conditions:**

@if($dentalExamination->carries_free)
- Carries Free
@endif

@if($dentalExamination->poor_oral_hygiene)
- Poor Oral Hygiene
@endif

@if($dentalExamination->gum_infection)
- Gum Infection
@endif

@if($dentalExamination->restorable_caries)
- Restorable Caries
@endif

@if($dentalExamination->other_condition)
- Other Condition: {{ $dentalExamination->other_condition }}
@endif

---

**Procedures:**

@if(is_array($dentalExamination->sealant_tooth) && count($dentalExamination->sealant_tooth) > 0)
- **Sealant Tooth(s):**
    <ul>
        @foreach($dentalExamination->sealant_tooth as $tooth)
            <li>{{ $tooth }}: {{ $teethData[$tooth] ?? 'Unknown Tooth' }}</li>
        @endforeach
    </ul>
@endif

@if(is_array($dentalExamination->filling_tooth) && count($dentalExamination->filling_tooth) > 0)
- **For Filling:**
    <ul>
        @foreach($dentalExamination->filling_tooth as $tooth)
            <li>{{ $tooth }}: {{ $teethData[$tooth] ?? 'Unknown Tooth' }}</li>
        @endforeach
    </ul>
@endif

@if(is_array($dentalExamination->extraction_tooth) && count($dentalExamination->extraction_tooth) > 0)
- **For Extraction:**
    <ul>
        @foreach($dentalExamination->extraction_tooth as $tooth)
            <li>{{ $tooth }}: {{ $teethData[$tooth] ?? 'Unknown Tooth' }}</li>
        @endforeach
    </ul>
@endif

@if(is_array($dentalExamination->endodontic_tooth) && count($dentalExamination->endodontic_tooth) > 0)
- **For Endodontic Treatment/RCT:**
    <ul>
        @foreach($dentalExamination->endodontic_tooth as $tooth)
            <li>{{ $tooth }}: {{ $teethData[$tooth] ?? 'Unknown Tooth' }}</li>
        @endforeach
    </ul>
@endif

@if(is_array($dentalExamination->radiograph_tooth) && count($dentalExamination->radiograph_tooth) > 0)
- **For Radiograph/X-ray:**
    <ul>
        @foreach($dentalExamination->radiograph_tooth as $tooth)
            <li>{{ $tooth }}: {{ $teethData[$tooth] ?? 'Unknown Tooth' }}</li>
        @endforeach
    </ul>
@endif

@if(is_array($dentalExamination->prosthesis_tooth) && count($dentalExamination->prosthesis_tooth) > 0)
- **Needs Prosthesis/Denture:**
    <ul>
        @foreach($dentalExamination->prosthesis_tooth as $tooth)
            <li>{{ $tooth }}: {{ $teethData[$tooth] ?? 'Unknown Tooth' }}</li>
        @endforeach
    </ul>
@endif

@if($dentalExamination->personal_attention)
- **Need Personal Attention in Tooth Brushing**
@endif

@if($dentalExamination->oral_prophylaxis)
- **For Oral Prophylaxis**
@endif

@if($dentalExamination->fluoride_application)
- **For Fluoride Application**
@endif

@if($dentalExamination->gum_treatment)
- **For Gum/Periodontal Treatment**
@endif

@if($dentalExamination->ortho_consultation)
- **For Orthodontic Consultation**
@endif

@if($dentalExamination->medical_clearance)
- **Medical Clearance:** Required
@endif

@if($dentalExamination->other_recommendation)
- **Other Recommendation:** {{ $dentalExamination->other_recommendation }}
@endif

@component('mail::button', ['url' => url('/medical-records')])
View Medical Records
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
