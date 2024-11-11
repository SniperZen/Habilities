<!DOCTYPE html>
<html>
<head>
    <title>New Appointment Request</title>
</head>
<body>
    <h1>New Appointment Request</h1>
    <p>Hi {{ $appointment->user->name }},</p>
    <p>A new appointment request has been made by {{ $appointment->user->name }}.</p>
    <p>Details:</p>
    <ul>
        <li>Mode: {{ $appointment->mode }}</li>
        <li>Note: {{ $appointment->note }}</li>
    </ul>
    <p>Please review the request.</p>
    <p>Thank you!</p>
</body>
</html>
