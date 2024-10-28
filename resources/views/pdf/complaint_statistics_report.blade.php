<!-- resources/views/pdf/complaint_statistics_report.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Complaints Statistics Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px; /* Adjust as needed */
            height: auto;
        }
        .header h2 {
            margin: 10px 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .report-info {
            margin-bottom: 20px;
        }
        .report-info span {
            display: inline-block;
            margin-right: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #007bff;
            color: white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .summary {
            font-size: 14px;
        }
        .summary span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
        <h2>Complaints Statistics Report</h2>
        <p>{{ $report_period }} Report for {{ $report_date }}</p>
    </div>

    <div class="report-info">
        <span><strong>Total Complaints:</strong> {{ $complaints->count() }}</span>
        <span><strong>Most Used Medicine:</strong> {{ $mostUsedMedicine }} ({{ $mostUsedMedicineCount }} times)</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Record Date</th>
                <th>Confine Status</th>
                <th>Go Home Status</th>
                <th>Description of Sickness</th>
                <th>Pain Assessment</th>
                <th>Medicine Given</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($complaints as $index => $complaint)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $complaint->first_name }}</td>
                    <td>{{ $complaint->last_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td> <!-- Updated -->
                    <td>{{ ucfirst(str_replace('_', ' ', $complaint->confine_status)) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $complaint->go_home)) }}</td>
                    <td>{{ $complaint->sickness_description }}</td>
                    <td>{{ $complaint->pain_assessment }}</td>
                    <td>{{ $complaint->medicine_given }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><span>Most Used Medicine:</span> {{ $mostUsedMedicine }} was used {{ $mostUsedMedicineCount }} times during this period.</p>
    </div>
</body>
</html>
