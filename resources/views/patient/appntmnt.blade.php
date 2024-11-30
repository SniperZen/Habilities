<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Page</title>
    <link rel="stylesheet" href="{{ asset('css/patient/appntmnt.css')}}">
    <script src="{{ asset('jss/modal.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>
<body>
    <div class="container">
    <main class="main-content">
            <a href="{{ route('patient.AppReq')}}" >
            <button class="reserve-btn">
                <i class="fas fa-plus"></i> Reserve an appointment
            </button>
            </a>

        <!-- Upcoming Appointments Table -->
        <div class="top-bar">
            <div>
                <h2>Appointment Schedule</h2>
            </div>
            <div>
                <div class="filter-buttons">
                        <button class="filter-btn">
                            <span class="filter-icon">🔍</span> Filters
                        </button>

                        <form id="acceptedFilterForm" action="{{ route('patient.appointments') }}" method="GET">
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
                                    <a href="#" data-filter="pending_only">All Pending</a>
                                    <a href="#" data-filter="all_upcoming">All Upcoming</a>
                                </div>
                            </div>
                            <input type="hidden" name="accepted_filter" id="acceptedFilterInput" value="{{ request('accepted_filter', 'all_upcoming') }}">
                            <input type="hidden" name="history_filter" value="{{ request('history_filter', 'all') }}">
                        </form>
                    </div>
            </div>
        </div>
        <div class="table">
        <table class="appointments">
            <thead>
                <tr>
                    <th>Therapist Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($upcomingAppointments->count() > 0)
                    @foreach($upcomingAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->therapist->name ?? '' }}</td>
                            <td>
                                @if(strtolower($appointment->status) === 'pending')
                                    Pending
                                @else
                                    {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') : '-' }}
                                @endif
                            </td>
                            <td>
                                @if(strtolower($appointment->status) === 'pending')
                                    Pending
                                @else
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                @endif
                            </td>
                            <td>{{ ucfirst($appointment->mode) }}</td>
                            <td>{{ ucfirst($appointment->status) }}</td>
                            <td>
                                <button class="view-btn" 
                                    data-therapist-name="{{ $appointment->therapist->name ?? 'N/A' }}"
                                    data-therapist-id="{{ $appointment->therapist_id }}"
                                    data-date="{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : 'Pending' }}"
                                    data-start-time="{{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('H:i') : 'Pending' }}"
                                    data-end-time="{{ $appointment->end_time ? \Carbon\Carbon::parse($appointment->end_time)->format('H:i') : 'Pending' }}"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-mode="{{ $appointment->mode }}"
                                    data-status="{{ $appointment->status }}"
                                    data-teletherapist-link="{{ $appointment->therapist->teletherapist_link ?? '' }}"
                                    onclick="openModal(this)">
                                    View
                                </button>
                                <button class="cancel-btn" data-appointment-id="{{ $appointment->id }}">Cancel</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="no-appointments" style="text-align: center; padding: 20px;">
                            No appointments scheduled
                        </td>
                    </tr>
                @endif
            </tbody>


        </table>
        </div>

        <div id="appointmentModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <h3>Appointment Details</h3>
                        </div>
                        <!-- In your appointmentModal div, update the bot div -->
                            <div class="bot">
                                <input type="hidden" id="appointmentId" name="appointmentId"> 
                                <p><label>Therapist Name:</label> <span id="therapist-name"></span></p>
                                <p><label>Date:</label> <span id="appointment-date"></span></p>
                                <p><label>Time:</label> <span id="appointment-time"></span></p>
                                <p><label>Status:</label> <span id="appointment-status"></span></p>
                                <p><label>Mode of Appointment:</label> 
                                    <input type="radio" name="mode" value="on-site" disabled id="mode-onsite"> On-site
                                    <input type="radio" name="mode" value="tele-therapy" disabled id="mode-teletherapy"> Tele-Therapy
                                </p>
                                
                                <!-- Static Address Section -->
                                <div id="address-section" style="display: none;">
                                    <p><label>Clinic Address:</label></p>
                                    <div class="address-container">
                                        <p id="address-text">2nd Floor, SM City San Jose Del Monte, Quirino Highway, San Jose del Monte City, Bulacan</p>
                                        <a id="google-maps-link" href="https://maps.app.goo.gl/dK1WGDNKRu9gr9jA7" target="_blank" class="maps-link">View on Google Maps</a>
                                    </div>
                                </div>

                                <!-- Teletherapy Section -->
                                <div id="teletherapy-section">
                                    <p><label>Tele-therapy link:</label></p>
                                    <div class="link-container">
                                        <a id="teletherapy-link" href="#" target="_blank">Join Video Call</a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="back-btn">Back</button>
                        <button class="cancel-appointment-btn">Cancel Appointment</button>
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

                            <p><label>Note to the therapist:</label></p>
                            <textarea id="cancellationNote" name="cancellationNote" placeholder="Type here..." rows="4"></textarea>
                        </div>
                    </div>
                            <div class="modal-footer">
                                <button type="button" class="back-btn">Back</button>
                                <!-- In the cancellation form modal -->
                                <button type="submit" class="cancel-appointment-btn2" disabled>Cancel Appointment</button>
                            </div>
                        </form>
                </div>    
            </div>
        </div>


        <div id="ConfirmationModal" class="modals">
            <div class="modal-contentss">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <h3>Appointment Cancellation</h3>
                        </div>
                        <div class="bot">
                            <p>Are you sure you want to cancel your appointment? <br>
                            Please confirm your decision by clicking “Yes” below. <br>
                            If you wish to keep your appointment, <br>
                            click “No” to return to the previous screen.
                            </p>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <button class="back-btn" id="no">No</button>
                        <button id="yes" class="yes">Yes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </main>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function validateCancellationForm() {
    const reason = document.getElementById('cancellationReason').value;
    const note = document.getElementById('cancellationNote').value.trim();
    const submitButton = document.querySelector('.cancel-appointment-btn2');
    
    // Enable button only if both fields are filled
    if (reason && note) {
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
        submitButton.style.cursor = 'pointer';
    } else {
        submitButton.disabled = true;
        submitButton.style.opacity = '0.7';
        submitButton.style.cursor = 'not-allowed';
    }
}

// Add event listeners to form fields
document.addEventListener('DOMContentLoaded', function() {
    const reasonSelect = document.getElementById('cancellationReason');
    const noteTextarea = document.getElementById('cancellationNote');
    
    // Initial validation
    validateCancellationForm();
    
    // Validate on reason change
    reasonSelect.addEventListener('change', validateCancellationForm);
    
    // Validate on note input
    noteTextarea.addEventListener('input', validateCancellationForm);
});
    function openModal(button) {
    const modal = document.getElementById('appointmentModal');
    const data = button.dataset;
    
    // Set basic appointment information
    document.getElementById('therapist-name').textContent = data.therapistName;
    document.getElementById('appointment-date').textContent = data.date;
    document.getElementById('appointment-time').textContent = data.startTime === 'Pending' ? 'Pending' : `${data.startTime} - ${data.endTime}`;
    document.getElementById('appointment-status').textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
    document.getElementById('appointmentId').value = data.appointmentId;
    
    // Set mode radio buttons
    document.getElementById('mode-onsite').checked = (data.mode === 'on-site');
    document.getElementById('mode-teletherapy').checked = (data.mode === 'tele-therapy');
    
    const linkElement = document.getElementById('teletherapy-link');
    const addressSection = document.getElementById('address-section');
    const teletherapySection = document.getElementById('teletherapy-section');
    
    // Handle display based on appointment mode and status
    if (data.mode === 'on-site') {
        // Show address section for on-site appointments
        addressSection.style.display = 'block';
        teletherapySection.style.display = 'none';
    } else if (data.mode === 'tele-therapy') {
        // Show teletherapy section for teletherapy appointments
        addressSection.style.display = 'none';
        teletherapySection.style.display = 'block';
        
        // Check if the appointment is pending
        if (data.status.toLowerCase() === 'pending' || data.date === 'Pending') {
            linkElement.style.display = 'inline';
            linkElement.href = '#';
            linkElement.textContent = 'Link will be available after appointment is confirmed';
            linkElement.classList.add('disabled');
        } else if (data.status.toLowerCase() === 'accepted') {
            const now = new Date();
            const [appointmentDate, appointmentTime] = [data.date, data.startTime];
            const appointmentDateTime = new Date(`${appointmentDate}T${appointmentTime}`);
            const appointmentEndTime = new Date(`${appointmentDate}T${data.endTime}`);
            
            // Calculate time difference in minutes
            const timeDifferenceMinutes = (appointmentDateTime - now) / (1000 * 60);
            
            if (data.teletherapistLink && data.teletherapistLink.trim() !== '') {
                if (timeDifferenceMinutes <= 10 && now < appointmentEndTime) {
                    linkElement.style.display = 'inline';
                    linkElement.href = data.teletherapistLink;
                    linkElement.textContent = 'Join Video Call';
                    linkElement.classList.remove('disabled');
                } else if (now > appointmentEndTime) {
                    linkElement.style.display = 'inline';
                    linkElement.href = '#';
                    linkElement.textContent = 'Session Ended';
                    linkElement.classList.add('disabled');
                } else {
                    linkElement.style.display = 'inline';
                    linkElement.href = '#';
                    linkElement.textContent = 'Link will be available 10 minutes before the session';
                    linkElement.classList.add('disabled');
                }
            } else {
                linkElement.style.display = 'inline';
                linkElement.href = '#';
                linkElement.textContent = 'Link not yet provided by therapist';
                linkElement.classList.add('disabled');
            }
        }
    }
    
    modal.style.display = 'block';
}



// Add this function to automatically update the link status
function updateLinkStatus() {
    if (document.getElementById('appointmentModal').style.display === 'block') {
        const button = document.querySelector('[data-appointment-id="' + document.getElementById('appointmentId').value + '"]');
        if (button) {
            openModal(button);
        }
    }
}

// Update link status every minute
setInterval(updateLinkStatus, 60000);

    // Close modal functions
    const closeModal = () => {
        appointmentModal.style.display = 'none';
    };

    closeBtn.addEventListener('click', closeModal);
    closeModalBtn.addEventListener('click', closeModal);

</script>
<script>
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
    setupFilter('historyFilterForm', 'dropdown-btn', 'dropdown-content', 'historyFilterInput');
});
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all cancel buttons
    const cancelButtons = document.querySelectorAll('.cancel-btn');
    const cancelModal = document.getElementById('cancelAppointmentModal');
    const confirmationModal = document.getElementById('ConfirmationModal');
    const cancellationForm = document.getElementById('cancellationForm');
    
    // Add click event to all cancel buttons
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-appointment-id');
            document.getElementById('cancelAppointmentId').value = appointmentId;
            cancelModal.style.display = 'block';
        });
    });

    // Handle back buttons
    document.querySelectorAll('.back-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            cancelModal.style.display = 'none';
            confirmationModal.style.display = 'none';
        });
    });

    // Handle cancellation form submission
    cancellationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        confirmationModal.style.display = 'block';
        cancelModal.style.display = 'none';
    });

    // Handle confirmation modal buttons
    document.getElementById('no').addEventListener('click', function() {
        confirmationModal.style.display = 'none';
        cancelModal.style.display = 'block';
    });

    document.getElementById('yes').addEventListener('click', function() {
        const appointmentId = document.getElementById('cancelAppointmentId').value;
        const reason = document.getElementById('cancellationReason').value;
        const note = document.getElementById('cancellationNote').value;

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('cancellationReason', reason);
        formData.append('cancellationNote', note);

        fetch(`/patient/cancel-appointment/${appointmentId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Store the success message in localStorage
            localStorage.setItem('toastMessage', 'Appointment cancelled successfully!');
            
            // Hide modals
            confirmationModal.style.display = 'none';
            cancelModal.style.display = 'none';
            
            // Reload the page immediately
            window.location.reload();
        })
        .catch(error => {
            // Store the error message in localStorage
            localStorage.setItem('toastMessage', 'Error cancelling appointment. Please try again.');
            localStorage.setItem('toastType', 'error');
            console.error('Error:', error);
            
            // Reload the page
            window.location.reload();
        });
    });

    // Check for stored toast message on page load
    const storedToastMessage = localStorage.getItem('toastMessage');
    const storedToastType = localStorage.getItem('toastType') || 'success';
    if (storedToastMessage) {
        showToast(storedToastMessage, storedToastType);
        localStorage.removeItem('toastMessage');
        localStorage.removeItem('toastType');
    }

    // Toast function using Toastify
    function showToast(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'success' ? "#4CAF50" : "#f44336",
        }).showToast();
    }
});
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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we should show the toast
    if (sessionStorage.getItem('showAppointmentToast')) {
        Toastify({
            text: "Appointment request has been sent successfully!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#4CAF50",
            stopOnFocus: true,
            close: true,
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            }
        }).showToast();

        // Remove the flag from sessionStorage
        sessionStorage.removeItem('showAppointmentToast');
    }
});
</script>
</html>
</x-patient-layout>