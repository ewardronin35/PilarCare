<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Health Examination Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            text-align: center;
            padding: 10px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }

        .header img {
            width: 70px;
            height: 70px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            padding: 0 20px;
        }

        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .details-table td.label {
            font-weight: bold;
            width: 200px;
            color: #007bff;
        }

        .section-title {
            font-size: 20px;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
            margin-top: 20px;
            padding-left: 20px;
        }

        .image-section {
            margin-bottom: 30px;
            padding-left: 20px;
        }

        .image-section img {
            border: 1px solid #ddd;
            padding: 5px;
            margin-right: 10px;
            margin-bottom: 10px;
            width: 150px;
            height: auto;
            display: inline-block;
        }

        .grid-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Pilar College Logo">
        @endif
        <h1>PILAR COLLEGE OF ZAMBOANGA CITY, INC.</h1>
        <p>R. T. Lim Boulevard, Zamboanga City</p>
        <p>Clinic Health Examination Documents</p>
    </div>

    <!-- Patient Details Section -->
    <table class="details-table">
        <tr>
            <td class="label">Name:</td>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <td class="label">Grade or Course:</td>
            <td>{{ $gradeOrCourse }}</td>
        </tr>
        <tr>
            <td class="label">Birthdate:</td>
            <td>{{ $birthdate }}</td>
        </tr>
        <tr>
            <td class="label">Address:</td>
            <td>{{ $address }}</td>
        </tr>
    </table>

    <!-- Health Examination Section -->
    @if(!empty($images['Health Examination']))
    <div class="image-section">
        <h2 class="section-title">Health Examination</h2>
        <div class="grid-container">
            @foreach($images['Health Examination'] as $healthExam)
                <img src="{{ $healthExam }}" alt="Health Examination Picture">
            @endforeach
        </div>
    </div>
    @endif

    <!-- X-ray Section -->
    @if(!empty($images['X-ray']))
    <div class="image-section">
        <h2 class="section-title">X-ray</h2>
        <div class="grid-container">
            @foreach($images['X-ray'] as $xray)
                <img src="{{ $xray }}" alt="X-ray Picture">
            @endforeach
        </div>
    </div>
    @endif

    <!-- Lab Exam Section -->
    @if(!empty($images['Lab Exam']))
    <div class="image-section">
        <h2 class="section-title">Lab Exam</h2>
        <div class="grid-container">
            @foreach($images['Lab Exam'] as $lab)
                <img src="{{ $lab }}" alt="Lab Result Picture">
            @endforeach
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        This report was generated by the Pilar College Clinic Health System.
    </div>
</body>
</html>
