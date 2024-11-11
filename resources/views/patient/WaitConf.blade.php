<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile - Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/WaitConf.css')}}">
</head>
<body>
<div class="container">
        <main class="main-content">
            <div class="confirmation-box">
                <h2>Wait for confirmation</h2>
                <p>Book a therapy session at Habilities with just easy steps! Please wait for your therapist to approve or respond to your appointment request.</p>
                <div class="message-sample">
                    <h3>Your appointment request has been sent!</h3>
                    <p>You may check your messages or appointment tab for updates. Below is a sample of the message you will receive.</p>
                    <div class="message">
                        <p>Hi! I received your appointment request. Let's see each other on the approved date indicated below. Thanks :)</p>
                        <div>
                            <strong>{{ $appointment->title ?? "Your Appointment" }}</strong><br>
                            {{ \Carbon\Carbon::parse($appointment->date)->format('jS F Y') }}<br>
                            {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}<br>
                            {{ $appointment->mode ?? "Tele-Therapy" }}
                        </div>
                    </div>
                </div>
                <button class="button">Check Messages</button>
            </div>
        </main>
    </div>
</body>
</html>
</x-patient-layout>