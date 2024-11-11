<!DOCTYPE html>
<html>
<head>
    <title>Your Appointment Has Been Updated</title>
</head>
<body>
    <h1>Your Appointment Has Been Updated</h1>
    <p>Dear {{ $appointment->user->name }},</p>
    <p>Your appointment has been rescheduled to</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</p>
    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</p>
    <p>Thank you!</p>
</body>
</html>
