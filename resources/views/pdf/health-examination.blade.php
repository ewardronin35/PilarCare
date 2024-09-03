<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Examination Report</title>
    <style>
        /* Add your PDF styles here */
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .images-section {
            margin-top: 30px;
        }

        .images-section img {
            width: 100%;
            max-width: 200px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .section-title {
            font-size: 1.5em;
            color: #007bff;
            margin-bottom: 10px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Health Examination Report</h1>
        <p>Role: {{ ucfirst($role) }}</p>
    </div>

    <div>
        <p><strong>Name:</strong> {{ $student->name }}</p>
        <p><strong>Section:</strong> {{ $student->section }}</p>
        <p><strong>Birthdate:</strong> {{ $healthExamination->birthdate }}</p>
        <p><strong>Address:</strong> {{ $healthExamination->address }}</p>
        <!-- Add other health examination fields as needed -->
    </div>

    @if(!empty($images['Health Examination']))
    <div class="images-section">
        <h2 class="section-title">Health Examination</h2>
        <img src="{{ $images['Health Examination'] }}" alt="Health Examination Picture">
    </div>
    @endif

    @if(!empty($images['X-ray']))
    <div class="images-section">
        <h2 class="section-title">X-ray</h2>
        @foreach($images['X-ray'] as $xray)
            <img src="{{ $xray }}" alt="X-ray Picture">
        @endforeach
    </div>
    @endif

    @if(!empty($images['Lab Exam']))
    <div class="images-section">
        <h2 class="section-title">Lab Exam</h2>
        @foreach($images['Lab Exam'] as $lab)
            <img src="{{ $lab }}" alt="Lab Result Picture">
        @endforeach
    </div>
    @endif
</body>
</html>
