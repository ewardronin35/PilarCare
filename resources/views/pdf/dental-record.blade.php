<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Dental Record Report</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            text-align: center;
            padding: 20px;
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

        .details-table th, .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #333;
        }

        .details-table th {
            font-weight: bold;
            background-color: #f7f7f7;
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

        .profile-picture {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 3px solid #007bff;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
        }

        .tooth-diagram {
            margin: 20px auto;
            text-align: center;
        }

        svg {
            width: 150px;
            height: 150px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <img src="{{ $logoBase64 }}" alt="Clinic Logo">
        <h1>Dental Record Report</h1>
        <p>Generated by the Pilar Clinic Health System</p>
    </div>

    <!-- Patient Profile Picture Section -->
    <div class="profile-picture">
        @if ($profilePictureBase64)
            <img src="{{ $profilePictureBase64 }}" alt="Profile Picture">
        @else
            <p>No profile picture available.</p>
        @endif
    </div>

    <!-- Patient Details Section -->
    <table class="details-table">
        <thead>
            <tr>
                <th>Detail</th>
                <th>Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $information->first_name }} {{ $information->last_name }}</td>
            </tr>
            <tr>
                <td><strong>ID Number:</strong></td>
                <td>{{ $dentalRecord->id_number }}</td>
            </tr>
            <tr>
                <td><strong>Grade & Section:</strong></td>
                <td>{{ $dentalRecord->grade_section }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Teeth Information Section -->
    <div class="section-title">Teeth Information</div>
    <table class="details-table">
        <thead>
            <tr>
                <th>Tooth Number</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Tooth Diagram</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teeth as $tooth)
                <tr>
                    <td>{{ $tooth->tooth_number }}</td>
                    <td>{{ $tooth->status }}</td>
                    <td>{{ $tooth->notes }}</td>
                    <td class="tooth-diagram">
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 10 H 90 V 90 H 10 Z" style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
    </svg>
</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        This report was generated by the Pilar College Clinic Health System.
    </div>
</body>
</html>
