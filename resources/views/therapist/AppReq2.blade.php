<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/AppReq2.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>
<body>

    <div class="content">
        
        <div class="icons">
            <div><a href="{{ route('therapist.AppReq')}}"><img src="{{ asset('images/icons/back.png') }}" alt="Back"></a></div>
            <h2>Appointment Request</h2>
        </div>

        <div class="profile">
            <div><img src="{{ Storage::url($appointment->profile_image) }}" alt="Profile Image" class="prof"></div>
            <div><p><strong>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</strong></p>
            <p>Patient ID: P-{{ str_pad($appointment->patient_id, 4, '0', STR_PAD_LEFT) }}</p></div>
        </div>

        <div class="appointment-details">
            <p><strong>Mode of Appointment:</strong> {{ $appointment->mode }}</p>
            <p><strong>Client Note:</strong></p>
            <p>{{ $appointment->note }}</p> 
        </div>

        <div class="buttons">
            <button class="decline-button" onclick="openDeclineModal()">Decline Request</button>

            <button class="schedule-button" onclick="openAddModal()">Add Appointment to Schedule</button>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Add Appointment Modal -->
    <div id="addAppointmentModal" class="modal">
        <div class="modal-content">
            <form action="{{ route('therapist.addAppointment', $appointment->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2>Add Appointment</h2>
                    <span class="close" onclick="closeAddModal()">&times;</span>
                </div>
                <div>
                    <div class="form-group">
                        <label for="newDate">Date</label>
                        <input type="date" id="newDate" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="newStartTime">Start Time</label>
                        <input type="time" id="newStartTime" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="newEndTime">End Time</label>
                        <input type="time" id="newEndTime" name="end_time" required>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="cancel-button" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="save-button">Add Appointment</button>
                </div>
            </form>
        </div>
    </div>
    

    <!-- Decline Request Confirmation Modal -->
    <div id="declineModal" class="modals">
        <div class="modal-contents">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <h2>Decline Request</h2>
                        <!-- <span class="close" onclick="closeDeclineModal()">&times;</span> -->
                    </div>
                    <div class="bot">
                        <p>You are about to decline an Appointment Request from a Patient. Are you sure you want to continue?</p>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button class="cancel-buttons" onclick="closeDeclineModal()">Cancel</button>
                    <form action="{{ route('therapist.declineRequest', $appointment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="confirm-button">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        // Add the toast function
        function showToast(message, type = 'success') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#28a745" : "#dc3545",
                stopOnFocus: true,
                close: true,
            }).showToast();
        }

        // Your existing JavaScript
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('newDate').setAttribute('min', today);

        function openAddModal() {
            var modal = document.getElementById("addAppointmentModal");
            modal.style.display = "block";
        }

        function closeAddModal() {
            var modal = document.getElementById("addAppointmentModal");
            modal.style.display = "none";
        }

        function openDeclineModal() {
            var modal = document.getElementById("declineModal");
            modal.style.display = "block";
        }

        function closeDeclineModal() {
            var modal = document.getElementById("declineModal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var declineModal = document.getElementById("declineModal");
            var addModal = document.getElementById("addAppointmentModal");

            if (event.target == declineModal) {
                closeDeclineModal();
            }
            if (event.target == addModal) {
                closeAddModal();
            }
        }

        // Check for the flags on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem('showDeclineToast')) {
                showToast('Request declined successfully!');
                sessionStorage.removeItem('showDeclineToast');
            }
            if (sessionStorage.getItem('showAddToast')) {
                showToast('Appointment added successfully!');
                sessionStorage.removeItem('showAddToast');
            }
        });

        // Modify the forms to set the flags before submission
        document.querySelector('form[action*="declineRequest"]').addEventListener('submit', function() {
            sessionStorage.setItem('showDeclineToast', 'true');
        });

        document.querySelector('form[action*="addAppointment"]').addEventListener('submit', function() {
            sessionStorage.setItem('showAddToast', 'true');
        });
    </script>

    <!-- Add this at the end of your body to handle Laravel's session flash messages -->
    @if(session('success'))
        <script>
            showToast("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            showToast("{{ session('error') }}", 'error');
        </script>
    @endif

</body>
</html>
</x-therapist-layout>