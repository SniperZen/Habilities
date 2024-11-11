<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Appointment</title>
    <link rel="stylesheet" href="{{ asset('css/patient/CompApp.css') }}">
</head>
<body>  
    <main class="main-content">
      
            <h2>Complete Your Appointment</h2>
            <p class="p">Book a therapy session at Habilities with just easy steps! Please complete your appointment details.</p>
            
            <div class="inside-content">
                <div class="progress-bar">
                    <div class="step inactive">
                        <div class="step-number">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step active">
                        <div class="step-number">2</div>
                        <div class="step-line"></div>
                    </div>
                </div>

                <div class="line"></div>
                <form id="appointmentForm" method="POST" action="{{ route('appointments.store') }}">
                    @csrf
                    <div class="appointment-box">
                        <div class="appointment-details">
                            <img src="{{ $therapist->profile_image ? asset('storage/' . $therapist->profile_image) : 'https://via.placeholder.com/80' }}" alt="Therapist">
                            <div>
                                <h3>{{ $therapist->first_name }} {{ $therapist->last_name }}</h3>
                                <p>{{ $therapist->specialization ?? 'Specialization not specified' }}</p>
                                <p><i class="icon-calendar"></i> {{ $therapist->availability ?? 'Availability not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mode-options">
                        <div><label class="moa" for="mode">Mode of Appointment:</label></div>
                        <div>
                            <input type="radio" id="on-site" name="mode" value="on-site" required checked>
                            <label for="on-site">On-site</label>
                        </div>
                        <div>
                            <input type="radio" id="tele-therapy" name="mode" value="tele-therapy" required>
                            <label for="tele-therapy">Tele-Therapy</label>
                        </div>
                    </div>
                    <div class="cont">
                        <label class="note" for="note">Note to the therapist (optional)</label>
                        <textarea id="note" name="note" class="note" placeholder="Type here..."></textarea>
                        <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
                    </div>

                    <div class="buttons">
                        <button type="button" class="buttonc" onclick="window.history.back();">Back</button>
                        <button type="submit" class="button">Confirm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal HTML -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <h2>Appointment Request <br> has been sent!</h2>
                <p>Please wait for your therapist to approve your appointment request. A confirmation, along with appointment details, will be sent to you once the request is accepted. You may check your email or appointment tab for updates.</p>
                <a href="{{ route('patient.appntmnt') }}"><button id="closeModalButton">Okay</button></a>
            </div>
        
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#confirmationModal').show();
                } else {
                    alert('An error occurred. Please try again.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    $('#closeModalButton').on('click', function() {
        $('#confirmationModal').hide();
        window.location.href = "{{ route('patient.appntmnt') }}";
    });
});
</script>

</body>
</html>
    </x-patient-layout>