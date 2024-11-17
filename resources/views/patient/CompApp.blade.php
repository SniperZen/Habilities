<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Appointment</title>
    <link rel="stylesheet" href="{{ asset('css/patient/CompApp.css') }}">
</head>
<style>
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    position: relative;
    margin: 15% auto;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

.button-secondary {
    padding: 0.5rem 1.5rem;
    background-color: #f0f0f0;
    color: #333;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
}

.button-primary {
    padding: 0.5rem 1.5rem;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.button-secondary:hover {
    background-color: #e0e0e0;
}

.button-primary:hover {
    background-color: #45a049;
}

/* Show modal when active */
.modal.show {
    display: block;
}

</style>
<body>  
    <main class="main-content">
                <div class="inner">
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
        </div>

<!-- Are You Sure Modal -->
<div id="areYouSureModal" class="modal">
    <div class="modal-content">
        <h2>Confirm Appointment</h2>
        <p>Are you sure you want to submit this appointment request?</p>
        <div class="modal-buttons">
            <button type="button" id="cancelButton" class="button-secondary">Cancel</button>
            <button type="button" id="proceedButton" class="button-primary">Proceed</button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <h2>Appointment Request <br> has been sent!</h2>
        <p>Please wait for your therapist to approve your appointment request. A confirmation, along with appointment details, will be sent to you once the request is accepted. You may check your email or appointment tab for updates.</p>
        <a href="{{ route('patient.appntmnt') }}"><button id="closeModalButton">Okay</button></a>
    </div>
</div>


    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();
        $('#areYouSureModal').addClass('show');
    });

    $('#cancelButton').on('click', function() {
        $('#areYouSureModal').removeClass('show');
    });

    $('#proceedButton').on('click', function() {
        $('#areYouSureModal').removeClass('show');
        
        $.ajax({
            url: $('#appointmentForm').attr('action'),
            method: 'POST',
            data: $('#appointmentForm').serialize(),
            success: function(response) {
                if (response.success) {
                    $('#confirmationModal').addClass('show');
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
        $('#confirmationModal').removeClass('show');
        window.location.href = "{{ route('patient.appntmnt') }}";
    });

    // Close modal when clicking outside
    $(window).on('click', function(event) {
        if ($(event.target).hasClass('modal')) {
            $('.modal').removeClass('show');
        }
    });
});


</script>

</body>
</html>
    </x-patient-layout>