<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/AppSched.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Add these in your layout head section -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


   
</head>
<style>

.AppSched td[colspan="8"] {
    font-size: 16px;
    color: #666;
    background-color: #f9f9f9;
    border: none;
}


</style>
<body>
    <div class="content">
        <div class="tabs">
            <a href="{{ route('therapist.AppReq') }}">
                <div class="tab tab1">Appointment Requests</div>
            </a>
            <div class="tab active">Appointment Schedule</div>
        </div>
        <div class="asSort">
            <div class="section-title">Appointment Schedule</div>
                <div class="filter-buttons">
                        <button class="filter-btn">
                            <span class="filter-icon">🔍</span> Filters
                        </button>
                        <!-- Filter for Accepted Appointments -->
                        <form id="acceptedFilterForm" action="{{ route('therapist.AppSched') }}" method="GET">
                            <div class="dropdown-wrapper">
                                <button type="button" class="dropdown-btn">
                                    {{ ucfirst(str_replace('_', ' ', request('accepted_filter', 'all upcoming'))) }}
                                </button>
                                <div class="dropdown-content">
                                    <a href="#" data-filter="today">Today</a>
                                    <a href="#" data-filter="tomorrow">Tomorrow</a>
                                    <a href="#" data-filter="this_week">This Week</a>
                                    <a href="#" data-filter="next_week">Next Week</a>
                                    <a href="#" data-filter="this_month">This Month</a>
                                    <a href="#" data-filter="next_month">Next Month</a>
                                    <a href="#" data-filter="all_upcoming">All Upcoming</a>
                                </div>
                            </div>
                            <input type="hidden" name="accepted_filter" id="acceptedFilterInput" value="{{ request('accepted_filter', 'all_upcoming') }}">
                            <input type="hidden" name="history_filter" value="{{ request('history_filter', 'all') }}">
                        </form>
                </div>
        </div>
        <div class="table-wrapper">
        <table class="AppSched">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Mode</th>
                    <th>Tele-Therapy</th>
                    <th>OTF</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($acceptedAppointments->isEmpty())
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">No appointments found</td>
                    </tr>
                @else
                    @foreach($acceptedAppointments as $appointment)
                        @if($appointment->status != 'declined')
                            <tr data-id="{{ $appointment->id }}">
                                <td>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                                <td>{{ $appointment->mode }}</td>
                                <td>
                                    <a href="{{$appointment->teletherapist_link}}" target="_blank">
                                    <svg class="link" xmlns="http://www.w3.org/2000/svg" fill="#4F4A6E" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4F4A6E" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    </a>
                                </td>
                                <!-- Replace your existing OTF button with this -->
                                <td>
                                    <a href="{{ route('therapist.feedback2', [
                                        'patient_id' => $appointment->patient_id,
                                        'patient_name' => $appointment->first_name . ' ' . $appointment->middle_name . ' ' . $appointment->last_name
                                    ]) }}" class="otf-link">
                                        <img class="otf" src="{{ asset('images/icons/otf.png') }}" alt="Feedback Icon">
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('therapist.cancelAppointment', $appointment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="status-button cancelled" onclick="openCancelModal('{{ $appointment->id }}')">Cancel</button>
                                    </form>
                                    <button type="button" class="status-button finished" onclick="openFinishModal('{{ $appointment->id }}')">Finish</button>
                                    <button class="view" onclick="openModal('{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}', '{{ $appointment->patient_id }}', '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}', '{{ $appointment->id }}')">Edit</button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>

        </div>

        <!-- The Modal -->
        <div id="myModal" class="modals">
            <div class="modal-contents">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <!-- <span class="close" onclick="closeModal()">&times;</span> -->
                            <h2>Edit Appointment Information</h2>
                        </div>
                        <div class="bot">
                        <form id="appointmentForm" method="POST" action="{{ route('therapist.updateAppointment', ':appointmentId') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="appointmentId" name="appointmentId">
                            <div class="form-group">
                                <label for="patientName">Patient Name</label>
                                <input type="text" id="patientName" disabled name="patientName">
                            </div>
                            <div class="form-group">
                                <label for="patientId">Patient ID</label>
                                <input type="text" id="patientId" disabled name="patientId">
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" id="date" name="date" required>
                            </div>
                            <div class="form-group time">
                                <label for="time">Time</label>
                                <input type="time" id="startTime" name="start_time" required> to
                                <input type="time" id="endTime" name="end_time" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel-button" onclick="closeModal()">Cancel</button>
                                <button type="submit" class="save-button">Save and Send to Patient</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Appointment Cancellation Modal -->
        <div id="cancelAppointmentModal" class="modals">
            <div class="modal-contents">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <h3>Appointment Cancellation</h3>
                            </div>
                        <form id="cancellationForm" method="POST">
                            @csrf
                            <div class="bot">
                                <input type="hidden" id="cancelAppointmentId" name="appointmentId">
                                <p><label>Reason for cancellation:</label></p>
                                <select id="cancellationReason" name="cancellationReason" required>
                                    <option value="">Select...</option>
                                    <option value="Not feeling well">Not feeling well</option>
                                    <option value="Conflict in schedule">Conflict in schedule</option>
                                    <option value="Other">Other</option>
                                </select>

                                <p><label>Note to the Patient (optional):</label></p>
                                <textarea id="cancellationNote" name="cancellationNote" placeholder="Type here..." rows="4"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="back-btn">Back</button>
                        <button type="submit" class="cancel-appointment-btn2">Cancel Appointment</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

 <!-- Finish Modal -->
<div id="finishModal" class="modals" style="display: none;">
    <div class="modal-contents">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Finish Appointment</h2>
                </div>
                <div class="bot">
                    <p>You are about to mark this appointment as finished. Are you sure you want to continue?</p>
                </div>
            </div>
            <div class="modal-buttons">
                <button class="cancel-buttons" onclick="closeFinishModal()">Cancel</button>
                <form id="finishForm" action="#" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="confirm-button">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>



        <!-- Cancel Modal -->
        <div id="cancelModal" class="modals">
            <div class="modal-contents">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <h2>Cancel Appointment</h2>
                        </div>
                        <div class="bot">
                            <p>You are about to cancel this appointment. Are you sure you want to continue?</p>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <button class="cancel-buttons" onclick="closeCancelModal()">Cancel</button>
                        <form id="cancelForm" action="#" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="confirm-button">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Function to open the modal and set patient details
            function openModal(patientName, patientId, date, startTime, endTime, appointmentId) {
                    document.getElementById("patientName").value = patientName;
                    document.getElementById("patientId").value = "P-" + String(patientId).padStart(4, '0');
                    document.getElementById("date").value = date;
                    document.getElementById("startTime").value = startTime;
                    document.getElementById("endTime").value = endTime;
                    document.getElementById("appointmentId").value = appointmentId;

                    var modal = document.getElementById("myModal");
                    modal.style.display = "block";

                    // Update the form action
                    var form = document.getElementById("appointmentForm");
                    form.action = form.action.replace(':appointmentId', appointmentId);
                }


            // Function to close the modal
            function closeModal() {
                var modal = document.getElementById("myModal");
                modal.style.display = "none";
            }

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];
            
            document.addEventListener('DOMContentLoaded', function () {
    function setupFilter(formId, btnClass, contentClass, inputId) {
        const form = document.getElementById(formId);
        const filterButton = form.querySelector(`.${btnClass}`);
        const dropdownContent = form.querySelector(`.${contentClass}`);
        const dropdownItems = dropdownContent.querySelectorAll('a');
        const filterInput = document.getElementById(inputId);

        filterButton.addEventListener('click', function (event) {
            event.preventDefault();
            dropdownContent.classList.toggle('open');
            filterButton.classList.toggle('active');
        });

        document.addEventListener('click', function (event) {
            if (!filterButton.contains(event.target) && !dropdownContent.contains(event.target)) {
                dropdownContent.classList.remove('open');
                filterButton.classList.remove('active');
            }
        });

        dropdownItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const filter = this.getAttribute('data-filter');
                filterButton.textContent = this.textContent;
                filterInput.value = filter;
                dropdownContent.classList.remove('open');
                filterButton.classList.remove('active');
                form.submit();
            });
        });
    }

    // Setup filters for both sections
    setupFilter('acceptedFilterForm', 'dropdown-btn', 'dropdown-content', 'acceptedFilterInput');
});

//end of filters


// Function to show toast notification
function showToast(message, backgroundColor) {
    Toastify({
        text: message,
        duration: 3000, // Duration in milliseconds
        close: true,
        gravity: "top", // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`
        backgroundColor: backgroundColor || "#007bff", // Default color
    }).showToast();
}

function setToastMessage(message, type) {
    localStorage.setItem('toastMessage', message);
    localStorage.setItem('toastType', type);
}


// Handle cancellation form submission
document.getElementById('cancellationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setToastMessage("Appointment canceled successfully!", 'success'); // Green for success
            document.getElementById('cancelAppointmentModal').style.display = 'none';
            window.location.reload();
        } else {
            setToastMessage("Failed to cancel appointment.", "#dc3545"); // Red for error
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        setToastMessage("An error occurred. Please try again.", "#dc3545");
        window.location.reload();
    });
});

// Handle finish form submission
document.getElementById('finishForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = this;
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value // Include CSRF token
        },
        body: JSON.stringify({}) // Send an empty body since we're just updating status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setToastMessage("Appointment marked as finished!", 'success'); // Green for success
            location.reload();
        } else {
            setToastMessage("Error updating appointment status.", "#dc3545"); // Red for error
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        setToastMessage("An error occurred while updating the appointment status.", "#dc3545");
        location.reload();
    });
});

// Modify the appointment update form submission
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            setToastMessage("Appointment updated successfully!", 'success'); // Green for success
            closeModal();
            location.reload();
        } else {
            setToastMessage("Failed to update appointment. Please try again.", "#dc3545"); // Red for error
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        setToastMessage("An error occurred. Please try again.", "#dc3545");
        location.reload();
    });
});


           
// Function to open cancel modal
function openCancelModal(appointmentId) {
    event.preventDefault(); // Prevent form submission
    const modal = document.getElementById('cancelAppointmentModal');
    const form = document.getElementById('cancellationForm');
    
    form.action = `/therapist/cancel-appointment/${appointmentId}`;
    document.getElementById('cancelAppointmentId').value = appointmentId;
    modal.style.display = 'block';
}

// Close cancel modal and reload page
function closeCancelModal() {
    document.getElementById('cancelAppointmentModal').style.display = 'none';
    window.location.reload();
}


// Handle back button in cancel modal and reload
document.querySelector('.back-btn').addEventListener('click', function() {
    document.getElementById('cancelAppointmentModal').style.display = 'none';
});

// Handle back button in cancel modal
document.querySelector('.back-btn').addEventListener('click', function() {
    document.getElementById('cancelAppointmentModal').style.display = 'none';
});



function openFinishModal(appointmentId) {
    // Set the form action to the appropriate route
    const form = document.getElementById('finishForm');
    form.action = `/appointments/${appointmentId}/finish`; // Set the action URL

    // Show the modal
    document.getElementById('finishModal').style.display = 'block';
}

function closeFinishModal() {
    // Hide the modal
    document.getElementById('finishModal').style.display = 'none';
}

    const today = new Date().toISOString().split('T')[0];
    // Set the min attribute of the input to today's date
    document.getElementById('date').setAttribute('min', today);
        </script>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    const message = localStorage.getItem('toastMessage');
    const type = localStorage.getItem('toastType');

    if (message) {
        showToast(message, type);
        // Clear the message from local storage
        localStorage.removeItem('toastMessage');
        localStorage.removeItem('toastType');
    }
});

</script>

    </div>
    <script>
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
</script>

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