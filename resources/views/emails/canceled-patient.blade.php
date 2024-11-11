<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment Cancellation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .details-row {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Appointment Cancellation Notice</h2>
    </div>

    <div class="content">
        <p>Dear {{ $appointment->therapist->name }},</p>
        
        <p>This is to inform you that an appointment has been cancelled by the patient.</p>

        <div class="details">
            <div class="details-row">
                <span class="label">Patient Name:</span> 
                <span>{{ $patientName }}</span>
            </div>
            <div class="details-row">
                <span class="label">Date:</span> 
                <span>{{ \Carbon\Carbon::parse($appointmentDate)->format('F j, Y') }}</span>
            </div>
            <div class="details-row">
                <span class="label">Time:</span> 
                <span>{{ \Carbon\Carbon::parse($appointmentTime)->format('g:i A') }}</span>
            </div>
            <div class="details-row">
                <span class="label">Reason for Cancellation:</span> 
                <span>{{ $cancellationReason }}</span>
            </div>
            @if($cancellationNote)
            <div class="details-row">
                <span class="label">Additional Note:</span> 
                <span>{{ $cancellationNote }}</span>
            </div>
            @endif
        </div>

        <p>You can log in to the system to view more details.</p>

        <a href="{{ route('therapist.dashboard') }}" class="button">View Dashboard</a>
    </div>

    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }}. Please do not reply to this email.</p>
    </div>
</body>
</html>
