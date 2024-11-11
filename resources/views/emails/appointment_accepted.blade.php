<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #395886;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .details {
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Appointment Confirmed</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $appointment->patient->name }},</p>
            
            <p>Your appointment has been confirmed with the following details:</p>
            
            <div class="details">
                <p><strong>Therapist:</strong> {{ $appointment->therapist->name }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</p>
                <p><strong>Mode:</strong> {{ ucfirst($appointment->mode) }}</p>
                
                @if($appointment->mode === 'tele-therapy' && $appointment->therapist->teletherapist_link)
                    <p><strong>Teletherapy Link:</strong> <a href="{{ $appointment->therapist->teletherapist_link }}">{{ $appointment->therapist->teletherapist_link }}</a></p>
                @endif
            </div>

            <p><strong>Important Notes:</strong></p>
            <ul>
                @if($appointment->mode === 'on-site')
                    <li>Please arrive 10 minutes before your scheduled appointment time.</li>
                    <li>Don't forget to bring any relevant medical records or documentation.</li>
                @else
                    <li>Please test your internet connection before the session.</li>
                    <li>Ensure you have a quiet, private space for your teletherapy session.</li>
                    <li>Click the teletherapy link 5 minutes before your scheduled time.</li>
                @endif
                <li>If you need to cancel or reschedule, please do so at least 24 hours in advance.</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you have any questions, please contact us through our website or call our office.</p>
        </div>
    </div>
</body>
</html>
