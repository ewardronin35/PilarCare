<!DOCTYPE html>
<html>
<head>
    <title>Dental Examination Report</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #ffffff;
        }

        /* Header Section */
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 26px;
            color: #333;
        }

        .header p {
            margin: 0;
            font-size: 16px;
            color: #666;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            font-size: 22px;
            color: #0056b3;
            margin-bottom: 20px;
        }

        /* Summary Section */
        .summary-section {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .summary-box {
            background-color: #f7f7f7;
            padding: 15px 20px;
            border-radius: 8px;
            width: 30%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #28a745;
        }

        .summary-box p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Appointments Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            color: #333;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .summary-section {
                flex-direction: column;
                align-items: center;
            }

            .summary-box {
                width: 80%;
                margin-bottom: 20px;
            }
        }

        /* Additional Styles for Dental Exam Report */
        .section-title {
            font-weight: bold;
            margin-top: 10px;
        }
        .field-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        .input-field {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid black;
        }
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid black;
        }
        .remarks {
            margin-top: 10px;
        }
        .signature {
            margin-top: 20px;
            text-align: center;
        }
        .return-slip {
            margin-top: 30px;
            border-top: 1px solid black;
            padding-top: 10px;
        }
        .signature-line {
            display: inline-block;
            width: 300px;
            border-bottom: 1px solid black;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        @if(!empty($logoBase64))
            <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
        @endif
        <h2>Pilar College of Zamboanga City, Inc.</h2>
        <p>R.T. Lim Boulevard, Zamboanga City</p>
        <h3>Dental Examination Findings and Recommendation</h3>
    </div>

    <!-- Examination Details -->
    <div class="section-title">
        Date of Examination: <span class="input-field">{{ \Carbon\Carbon::parse($dentalExamination->date_of_examination)->format('F d, Y') }}</span>
    </div>

    <div class="section-title">
        Name: <span class="input-field">{{ $dentalExamination->firstname }} {{ $dentalExamination->lastname }}</span>
        Grade and Section: <span class="input-field">{{ $dentalExamination->grade_section }}</span>
    </div>

    <div class="section-title">
        Birthdate: <span class="input-field">{{ \Carbon\Carbon::parse($dentalExamination->birthdate)->format('F d, Y') }}</span>
        Age: <span class="input-field">{{ $dentalExamination->age }}</span>
    </div>

    <!-- Oral Examination Conditions -->
    <div class="section-title">Oral Examination Revealed the Following Conditions:</div>
    <p>
        <span class="checkbox">@if($dentalExamination->carries_free) &#10003; @endif</span> Carries-Free<br>
        <span class="checkbox">@if($dentalExamination->poor_oral_hygiene) &#10003; @endif</span> Poor Oral Hygiene (MATERIA, ALBA, CALCULUS, STAIN)<br>
        <span class="checkbox">@if($dentalExamination->gum_infection) &#10003; @endif</span> Gum Infection (Gingivitis, Periodontal Pockets)<br>
        <span class="checkbox">@if($dentalExamination->restorable_caries) &#10003; @endif</span> Restorable Carious Tooth/Teeth<br>
        <span class="checkbox">@if($dentalExamination->other_condition) &#10003; @endif</span> Others (Specify): <span class="input-field">{{ $dentalExamination->other_condition }}</span>
    </p>

    <!-- Remarks and Recommendations -->
    <div class="section-title">Remarks and Recommendations:</div>
    <p>
        @if($dentalExamination->personal_attention)
            Need personal attention in tooth brushing <br>
        @endif
        @if($dentalExamination->oral_prophylaxis)
            For oral prophylaxis <br>
        @endif
        @if($dentalExamination->fluoride_application)
            For fluoride application <br>
        @endif
        @if($dentalExamination->extraction_tooth && count($dentalExamination->extraction_tooth) > 0)
            For extraction tooth #: <span class="input-field">
                @foreach($dentalExamination->extraction_tooth as $tooth)
                    {{ $tooth }} - {{ $teethData[$tooth] ?? 'Unknown' }},
                @endforeach
            </span> <br>
        @endif
        @if($dentalExamination->endodontic_tooth && count($dentalExamination->endodontic_tooth) > 0)
            For endodontic treatment tooth #: <span class="input-field">
                @foreach($dentalExamination->endodontic_tooth as $tooth)
                    {{ $tooth }} - {{ $teethData[$tooth] ?? 'Unknown' }},
                @endforeach
            </span> <br>
        @endif
        @if($dentalExamination->other_recommendation)
            For other recommendations: <span class="input-field">{{ $dentalExamination->other_recommendation }}</span> <br>
        @endif
    </p>

    <!-- Dentist Signature -->
    <div class="signature">
        {{ $dentalExamination->dentist_name }}<br>
        SCHOOL DENTIST
    </div>

    <!-- Return Slip -->
    <div class="return-slip">
        <div class="section-title">Return Slip</div>
        <p>Note: Please secure a dental certificate</p>
        <div class="section-title">
            Name of Student: <span class="input-field">{{ $dentalExamination->firstname }} {{ $dentalExamination->lastname }}</span>
            Grade & Section: <span class="input-field">{{ $dentalExamination->grade_section }}</span>
        </div>
        <p>Treatment/Procedures Done:</p>
        <p>1. <span class="input-field"></span></p>
        <p>2. <span class="input-field"></span></p>
        <p>3. <span class="input-field"></span></p>

        <div class="signature">
            NAME & SIGNATURE OF ATTENDING DENTIST<br>
            LICENSE NO.: <span class="input-field"></span>
        </div>
    </div>

</body>
</html>
