<!DOCTYPE html>
<html>
<head>
    <title> New Complaint Alert </title>
</head>
<body>
    <h2>Dear {{ $complaint->first_name }} {{ $complaint->last_name }},</h2>
    <p>We have received your complaint submitted on {{ $complaint->created_at->format('Y-m-d') }}.</p>

    <p><strong>Description of Sickness:</strong> {{ $complaint->sickness_description }}</p>
    <p><strong>Pain Assessment:</strong> {{ $complaint->pain_assessment }}</p>
    <p><strong>Confine Status:</strong> {{ ucwords(str_replace('_', ' ', $complaint->confine_status)) }}</p>
    <p><strong>Medicine Given:</strong> {{ $complaint->medicine_given }}</p>
    <p><strong>Status:</strong> {{ ucfirst($complaint->status) }}</p>

    @if($complaint->report_url)
        <p>You can view or download your clinic slip here:</p>
        <p><a href="{{ $complaint->report_url }}" target="_blank">View PDF</a></p>
    @endif

    <p>Thank you for reaching out to us.</p>

    <p>Best regards,<br>Clinic Team</p>
</body>
</html>
