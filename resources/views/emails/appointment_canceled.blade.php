<!DOCTYPE html>
<html>
<head>
    <title>Appointment Canceled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
        }
        .reason-box, .note-box {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
            border-radius: 4px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Appointment Has Been Canceled</h1>
    </div>

    <div class="content">
        <p>Dear {{ $appointment->user->name }},</p>
        
        <p>Your appointment scheduled for {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }} 
           at {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} 
           has been canceled.</p>

        @if(isset($appointment->cancellation_reason) && $appointment->cancellation_reason)
        <div class="reason-box">
            <strong>Reason for Cancellation:</strong>
            <p>{{ $appointment->cancellation_reason }}</p>
        </div>
        @endif

        @if(isset($appointment->cancellation_note) && $appointment->cancellation_note)
        <div class="note-box">
            <strong>Additional Note:</strong>
            <p>{{ $appointment->cancellation_note }}</p>
        </div>
        @endif

        <p>If you would like to schedule a new appointment, please visit our website or contact us directly.</p>

        <div class="footer">
            <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
            <p>Best regards,<br>
            Habilities Center for Intervention</p>
        </div>
    </div>
</body>
</html>
