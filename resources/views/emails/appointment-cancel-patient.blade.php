<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment Cancellation Notice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333333;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    .header {
      background-color: #f8f9fa;
      padding: 20px;
      text-align: center;
      border-radius: 5px 5px 0 0;
    }
    .content {
      background-color: #ffffff;
      padding: 20px;
    }
    .details {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin: 20px 0;
    }
    .reason-box {
      border-left: 4px solid #dc3545;
      padding: 10px;
      margin: 10px 0;
      background-color: #fff;
    }
    .footer {
      text-align: center;
      padding: 20px;
      font-size: 12px;
      color: #666;
      background-color: #f8f9fa;
      border-radius: 0 0 5px 5px;
    }
    @media only screen and (max-width: 600px) {
      .container {
        width: 100% !important;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2 style="color: #333; margin: 0;">Appointment Cancellation Notice</h2>
    </div>
    
    <div class="content">
      <p>Dear {{ $appointment->therapist->full_name }},</p>
      
      <p>This email is to notify you that a patient has cancelled their appointment with the following details:</p>
      
      <div class="details">
        <p><strong>Patient Name:</strong> {{ $appointment->patient->full_name }}</p>
        <p><strong>Scheduled Start Time:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('l, F j, Y h:i A') }}</p>
        <p><strong>Scheduled End Time:</strong> {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</p>
        <p><strong>Mode:</strong> {{ ucfirst($appointment->mode) }}</p>
        
        <div class="reason-box">
          <p><strong>Cancellation Reason:</strong><br>
          {{ $appointment->patient_reason }}</p>
          
          @if($appointment->patient_note)
          <p><strong>Additional Notes:</strong><br>
          {{ $appointment->patient_note }}</p>
          @endif
        </div>
      </div>

      <p>The appointment slot is now available for other bookings. You can check your updated schedule in the system.</p>
      
      <p>Best regards,<br>
      {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
      <p>This is an automated message. Please do not reply to this email.</p>
      <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
  </div>
</body>
</html>