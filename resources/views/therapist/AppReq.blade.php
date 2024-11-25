<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/AppReq.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButton = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');
    const dropdownItems = dropdownContent.querySelectorAll('a');
    const filterForm = document.getElementById('filterForm');
    const filterInput = document.getElementById('filterInput');

    filterButton.addEventListener('click', function () {
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
            filterForm.submit();
        });
    });
});

    </script>
<style>

#patientList {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
}

.patient-item {
    padding: 8px 12px;
    cursor: pointer;
}

.patient-item:hover {
    background-color: #f5f5f5;
}

.error-messages {
    color: #f44336;
    margin-bottom: 15px;
    background-color: rgba(244, 67, 54, 0.1);
    border-radius: 4px;
}

.error-messages ul {
    margin: 0;
    padding: 0;
}

.error-messages li {
    margin-bottom: 5px;
}

.error-messages li:last-child {
    margin-bottom: 0;
}
</style>


</head>
<body>
    <div class="content">
        <div class="tabs">
            <div class="tabi">
                <div class="tab active">Appointment Requests</div>
                <a href="{{ route('therapist.AppSched') }}"><div class="tab tab1">Appointment Schedule</div></a>
            </div>
        </div>

        <div class="arSort">
            <div class="section-title"><h2>Appointment Requests</h2></div>
            <div class="error-messages" style="color: red; margin-bottom: 15px;">
                @if ($errors->any())
                    <ul style="list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="filter-buttons">
                <div>
                    <button class="btn-primary" id="addAppointmentBtn"> <i class="fas fa-plus"></i>Add Appointment</button>
                </div>
                <button class="filter-btn">
                    <span class="filter-icon">🔍</span> Filters
                </button>

                <form id="filterForm" action="{{ route('therapist.AppReq') }}" method="GET">
                    <div class="dropdown-wrapper">
                        <button type="button" class="dropdown-btn">
                            {{ request('filter') ? ucfirst(str_replace('_', ' ', request('filter'))) : 'All' }}
                        </button>
                        <div class="dropdown-content">
                            <a href="#" data-filter="today">Today</a>
                            <a href="#" data-filter="yesterday">Yesterday</a>
                            <a href="#" data-filter="last_7_days">Last 7 Days</a>
                            <a href="#" data-filter="last_14_days">Last 14 Days</a>
                            <a href="#" data-filter="last_21_days">Last 21 Days</a>
                            <a href="#" data-filter="last_28_days">Last 28 Days</a>
                            <a href="#" data-filter="all">All</a>
                        </div>
                    </div>
                    <input type="hidden" name="filter" id="filterInput" value="{{ request('filter', 'all') }}">
                </form>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Received</th>
                        <th>Mode</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($appointments->isEmpty())
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">No appointment requests found</td>
                        </tr>
                    @else
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('F j, Y') }}</td>
                            <td>{{ $appointment->mode }}</td>
                            <td>{{ Str::limit($appointment->note ?? '-', 50) }}</td> <!-- Truncated message -->
                            <td>
                                <a href="{{ route('therapist.AppReq2', $appointment->id) }}">
                                    <button class="review-button">Review</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    
        <!-- Add Appointment Modal -->
<!-- Update the Add Appointment Modal section -->
<div class="modal" id="addAppointmentModal">
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <div class="modal-header"><h2>Add Appointment</h2></div>
                </div>
                <div class="bot">
                    <form action="{{ route('therapist.appointments.add') }}" method="POST" id="appointmentForm">
                        @csrf
                        <div class="form-group">
                            <label for="patientSearch">Search Patient<span style="color: red;">*</span></label>
                            <div class="search-container" style="position: relative;">
                                <input type="text" id="patientSearch" placeholder="Click to see all patients or type to search" autocomplete="off">
                                <input type="hidden" name="patient_id" id="patient_id" required>
                                <div id="patientList" class="patient-list-dropdown"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="appointmentMode">Mode of Appointment<span style="color: red;">*</span></label>
                            <select id="appointmentMode" name="mode" required>
                                <option value="on-site">On-Site</option>
                                <option value="tele-therapy">Tele-therapy</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="newDate">Date<span style="color: red;">*</span></label>
                            <input type="date" id="newDate" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="newStartTime">Start Time<span style="color: red;">*</span></label>
                            <select id="newStartTime" name="start_time" required>
                                <option value="">Select a date first</option>
                            </select>
                            <input type="hidden" id="formattedStartTime" name="formatted_start_time">
                        </div>
                        <div class="form-group">
                            <label for="newEndTime">End Time<span style="color: red;">*</span></label>
                            <select id="newEndTime" name="end_time" required>
                                <option value="">Select start time first</option>
                            </select>
                            <input type="hidden" id="formattedEndTime" name="formatted_end_time">
                        </div>
                        <div class="modal-buttons">
                            <button type="button" class="btn-secondary" id="closeModalBtn">Cancel</button>
                            <button type="submit" class="btn-primary">Add Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    <script>
        document.getElementById('addAppointmentBtn').addEventListener('click', function () {
            document.getElementById('addAppointmentModal').style.display = 'flex';
        });

        document.getElementById('closeModalBtn').addEventListener('click', function () {
            document.getElementById('addAppointmentModal').style.display = 'none';
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
// Filter dropdown functionality
document.addEventListener('DOMContentLoaded', function () {
    const filterButton = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');
    const dropdownItems = dropdownContent.querySelectorAll('a');
    const filterForm = document.getElementById('filterForm');
    const filterInput = document.getElementById('filterInput');

    // Filter dropdown toggle
    filterButton.addEventListener('click', function () {
        dropdownContent.classList.toggle('open');
        filterButton.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!filterButton.contains(event.target) && !dropdownContent.contains(event.target)) {
            dropdownContent.classList.remove('open');
            filterButton.classList.remove('active');
        }
    });

    // Handle filter selection
    dropdownItems.forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            const filter = this.getAttribute('data-filter');
            filterButton.textContent = this.textContent;
            filterInput.value = filter;
            dropdownContent.classList.remove('open');
            filterButton.classList.remove('active');
            filterForm.submit();
        });
    });
});

// Modal control
document.getElementById('addAppointmentBtn').addEventListener('click', function () {
    document.getElementById('addAppointmentModal').style.display = 'flex';
});

document.getElementById('closeModalBtn').addEventListener('click', function () {
    document.getElementById('addAppointmentModal').style.display = 'none';
});

// Main appointment functionality
$(document).ready(function() {
    const patientSearch = $('#patientSearch');
    const patientList = $('#patientList');
    const patientIdInput = $('#patient_id');
    const startTimeSelect = $('#newStartTime');
    const endTimeSelect = $('#newEndTime');
    const dateInput = $('#newDate');
    const formattedStartTime = $('#formattedStartTime');
    const formattedEndTime = $('#formattedEndTime');

    // Initialize date restrictions
    const today = new Date();
    const nextYear = new Date(today);
    nextYear.setFullYear(today.getFullYear() + 1);
    dateInput.attr('min', today.toISOString().split('T')[0]);
    dateInput.attr('max', nextYear.toISOString().split('T')[0]);

    // Patient search functionality
    patientSearch.on('focus', function() {
        $.ajax({
            url: '/search-users',
            method: 'GET',
            success: function(response) {
                displayPatientList(response);
            }
        });
    });

    patientSearch.on('input', function() {
        if ($(this).val().length > 0) {
            $.ajax({
                url: '/search-users',
                method: 'GET',
                data: { query: $(this).val() },
                success: function(response) {
                    displayPatientList(response);
                }
            });
        } else {
            patientList.hide();
        }
    });

    // Handle patient selection
    $(document).on('click', '.patient-item', function() {
        const patientId = $(this).data('id');
        const patientName = $(this).text();
        patientIdInput.val(patientId);
        patientSearch.val(patientName);
        patientList.hide();
        dateInput.prop('disabled', false);
        resetTimeSelections();
    });

    dateInput.on('change', async function() {
    const selectedDate = $(this).val();
    const patientId = patientIdInput.val();

    if (!patientId) {
        showToast('Please select a patient first', 'error');
        $(this).val('');
        return;
    }

    try {
        console.log('Selected date:', selectedDate);
        
        const response = await $.ajax({
            url: '{{ route("therapist.getAcceptedAppointments") }}',
            method: 'GET',
            data: {
                date: selectedDate,
                patient_id: patientId
            }
        });

        console.log('API Response:', response);
        
        if (response.status === 'success') {
            // Log each appointment date for debugging
            response.appointments.forEach(apt => {
                console.log('Appointment date:', apt.appointment_details.schedule.date);
                console.log('Comparing with selected:', moment(selectedDate).isSame(moment(apt.appointment_details.schedule.date, 'MMMM D, YYYY'), 'day'));
            });

            const availableSlots = generateAvailableTimeSlots(selectedDate, response.appointments);
            console.log('Available slots:', availableSlots);
            updateTimeSelectors(availableSlots);
        } else {
            console.error('API returned non-success status:', response);
            showToast('Error loading time slots', 'error');
        }
    } catch (error) {
        console.error('Error checking availability:', error);
        showToast('Error loading available time slots', 'error');
    }
});

    // Start time change handler
    startTimeSelect.on('change', function() {
        const selectedStartTime = $(this).val();
        if (selectedStartTime) {
            formattedStartTime.val(selectedStartTime);
            updateEndTimeOptions(selectedStartTime);
        }
    });

    // End time change handler
    endTimeSelect.on('change', function() {
        formattedEndTime.val($(this).val());
    });

    $('#appointmentForm').on('submit', async function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }

    const selectedDate = dateInput.val();
    const startTime = formattedStartTime.val();
    const endTime = formattedEndTime.val();
    const patientId = patientIdInput.val();
    const currentTherapistId = '{{ Auth::id() }}';

    try {
        const response = await $.ajax({
            url: '{{ route("therapist.getAcceptedAppointments") }}',
            method: 'GET',
            data: {
                patient_id: patientId,
                date: selectedDate
            }
        });

        const formattedSelectedDate = moment(selectedDate).format('MMMM D, YYYY');
        const selectedStartMoment = moment(startTime, 'HH:mm');
        const selectedEndMoment = moment(endTime, 'HH:mm');

        const hasConflict = response.appointments.some(apt => {
            if (apt.appointment_details.schedule.date === formattedSelectedDate) {
                // Check for both therapist and patient conflicts
                if (apt.participants.therapist.id == currentTherapistId || 
                    apt.participants.patient.id == patientId) {
                    
                    const aptStart = moment(apt.appointment_details.schedule.start, 'hh:mm A');
                    const aptEnd = moment(apt.appointment_details.schedule.end, 'hh:mm A');
                    
                    const hasTimeConflict = (
                        selectedStartMoment.isBetween(aptStart, aptEnd, null, '[]') ||
                        selectedEndMoment.isBetween(aptStart, aptEnd, null, '[]') ||
                        (selectedStartMoment.isSameOrBefore(aptStart) && selectedEndMoment.isSameOrAfter(aptEnd))
                    );

                    if (hasTimeConflict) {
                        // Provide specific message about the conflict
                        if (apt.participants.therapist.id == currentTherapistId) {
                            showToast('You already have an appointment at this time', 'error');
                        } else {
                            showToast('The patient already has an appointment at this time', 'error');
                        }
                        return true;
                    }
                }
            }
            return false;
        });

        if (hasConflict) {
            return;
        }

        this.submit();
    } catch (error) {
        console.error('Error validating appointment:', error);
        showToast('Error validating appointment time', 'error');
    }
});


    // Helper Functions
    function displayPatientList(patients) {
        let html = '';
        if (patients.length > 0) {
            patients.forEach(user => {
                html += `<div class="patient-item" data-id="${user.id}">${user.name}</div>`;
            });
        } else {
            html = '<div class="patient-item">No patients found</div>';
        }
        patientList.html(html).show();
    }

    function generateAvailableTimeSlots(selectedDate, existingAppointments) {
    const timeSlots = [
        { value: '09:00', display: '09:00 AM' },
        { value: '10:00', display: '10:00 AM' },
        { value: '11:00', display: '11:00 AM' },
        { value: '13:00', display: '01:00 PM' },
        { value: '14:00', display: '02:00 PM' },
        { value: '15:00', display: '03:00 PM' },
        { value: '16:00', display: '04:00 PM' },
        { value: '17:00', display: '05:00 PM' }
    ];

    // Format selected date consistently
    const selectedMoment = moment(selectedDate);
    const currentTherapistId = '{{ Auth::id() }}';
    const selectedPatientId = patientIdInput.val();
    
    return timeSlots.filter(slot => {
        const isSlotAvailable = !existingAppointments.some(apt => {
            // Parse the appointment date consistently
            const aptDate = moment(apt.appointment_details.schedule.date, 'MMMM D, YYYY');
            
            // Compare dates using isSame
            if (selectedMoment.isSame(aptDate, 'day')) {
                if (apt.participants.therapist.id == currentTherapistId || 
                    apt.participants.patient.id == selectedPatientId) {
                    
                    const aptStart = moment(apt.appointment_details.schedule.start, 'hh:mm A');
                    const aptEnd = moment(apt.appointment_details.schedule.end, 'hh:mm A');
                    const slotTime = moment(slot.value, 'HH:mm');
                    
                    // Check for time conflicts
                    return (
                        slotTime.isBetween(aptStart, aptEnd, null, '[]') ||
                        slotTime.clone().add(1, 'hour').isBetween(aptStart, aptEnd, null, '[]') ||
                        slotTime.clone().add(2, 'hours').isBetween(aptStart, aptEnd, null, '[]')
                    );
                }
            }
            return false;
        });

        // If we're checking today's date, also filter out past times
        if (selectedMoment.isSame(moment(), 'day')) {
            const currentTime = moment();
            const slotTime = moment(slot.value, 'HH:mm');
            if (slotTime.isBefore(currentTime)) {
                return false;
            }
        }
        
        return isSlotAvailable;
    });
}



    function updateTimeSelectors(availableSlots) {
        startTimeSelect.html('<option value="">Select start time</option>');
        endTimeSelect.html('<option value="">Select start time first</option>');

        availableSlots.forEach(slot => {
            startTimeSelect.append(`
                <option value="${slot.value}">${slot.display}</option>
            `);
        });

        startTimeSelect.prop('disabled', false);
    }

    function updateEndTimeOptions(startTime) {
        const selectedStartMoment = moment(startTime, 'HH:mm');
        endTimeSelect.html('<option value="">Select end time</option>');

        // Generate end time options (1-2 hours after start time)
        for (let i = 1; i <= 2; i++) {
            const endTime = selectedStartMoment.clone().add(i, 'hours');
            const value = endTime.format('HH:mm');
            const display = endTime.format('hh:mm A');
            endTimeSelect.append(`
                <option value="${value}">${display}</option>
            `);
        }

        endTimeSelect.prop('disabled', false);
    }

    function resetTimeSelections() {
        startTimeSelect.html('<option value="">Select a date first</option>');
        endTimeSelect.html('<option value="">Select start time first</option>');
        formattedStartTime.val('');
        formattedEndTime.val('');
    }

    function validateForm() {
        const patientId = patientIdInput.val();
        const date = dateInput.val();
        const startTime = formattedStartTime.val();
        const endTime = formattedEndTime.val();

        if (!patientId) {
            showToast('Please select a patient', 'error');
            return false;
        }

        if (!date) {
            showToast('Please select a date', 'error');
            return false;
        }

        if (!startTime || !endTime) {
            showToast('Please select both start and end times', 'error');
            return false;
        }

        if (endTime <= startTime) {
            showToast('End time must be after start time', 'error');
            return false;
        }

        return true;
    }
});

// Toast notification function
function showToast(message, type = 'success') {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: 'right',
        backgroundColor: type === 'success' ? "#4CAF50" : "#f44336",
    }).showToast();
}

</script>
<script>
    // Date restrictions for appointment
    document.addEventListener('DOMContentLoaded', function() {
        const appointmentDateInput = document.getElementById('appointmentDate');
        
        // Get today's date
        const today = new Date();
        const todayString = today.toISOString().split('T')[0];
        
        // Calculate date 1 year from today
        const nextYear = new Date();
        nextYear.setFullYear(today.getFullYear() + 1);
        const maxDate = nextYear.toISOString().split('T')[0];
        
        // Set both min and max attributes
        appointmentDateInput.setAttribute('min', todayString);
        appointmentDateInput.setAttribute('max', maxDate);
    });

    // Time validation for appointment
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        const startTime = document.getElementById('newStartTime').value;
        const endTime = document.getElementById('newEndTime').value;
        
        if (startTime >= endTime) {
            e.preventDefault();
            showToast('End time must be later than start time', 'error');
        }
    });

    // Toast function if you haven't defined it yet
    function showToast(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: 'right',
            backgroundColor: type === 'success' ? "#4CAF50" : "#f44336",
        }).showToast();
    }
</script>

</body>
</html>
</x-therapist-layout>
