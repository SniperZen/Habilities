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
        <!-- Header Section -->
        <div class="icons">
            <div>
                <a href="{{ route('therapist.AppReq')}}">
                    <img src="{{ asset('images/icons/back.png') }}" alt="Back">
                </a>
            </div>
            <h2>Appointment Request</h2>
        </div>

        <!-- Profile Section -->
        <div class="profile">
            <div>
                <img src="{{ Storage::url($appointment->profile_image) }}" alt="Profile Image" class="prof">
            </div>
            <div>
                <p><strong>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</strong></p>
                <p>Patient ID: P-{{ str_pad($appointment->patient_id, 4, '0', STR_PAD_LEFT) }}</p>
                @if($appointment->account_type === 'child')
                    <span class="supervised-badge">Supervised Account</span>
                @endif
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="appointment-details">
            <p><strong>Mode of Appointment:</strong> {{ $appointment->mode }}</p>
            <p><strong>Client Note:</strong></p>
            <p>{{ $appointment->note }}</p> 
        </div>

        <!-- Action Buttons -->
        <div class="buttons">
            <button class="decline-button" onclick="openDeclineModal()">Decline Request</button>
            <button class="schedule-button" onclick="openAddModal()">Add Appointment to Schedule</button>
        </div>

        <!-- Error Messages -->
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
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <h2>Add Appointment</h2>
                    </div>
                    <div class="bot">
                        <!-- Replace the form section in the Add Appointment Modal with this: -->
                            <form action="{{ route('therapist.addAppointment', $appointment->id) }}" method="POST" id="appointmentForm">
                            @csrf
                            <div class="form-group">
                                <label for="newDate">Date<span style="color: red;">*</span></label>
                                <input type="date" id="newDate" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="newStartTime">Start Time<span style="color: red;">*</span></label>
                                <select id="newStartTime" name="start_time" required>
                                    <option value="">Select a date first</option>
                                </select>
                                <!-- Hidden input for formatted start time -->
                                <input type="hidden" id="formattedStartTime" name="start_time">
                            </div>
                            <!-- Replace the end time form group with this -->
                            <div class="form-group">
                                <label for="newEndTime">End Time<span style="color: red;">*</span></label>
                                <select id="newEndTime" required>
                                    <option value="">Select start time first</option>
                                </select>
                                <!-- Hidden input for formatted end time -->
                                <input type="hidden" id="formattedEndTime" name="end_time">
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="cancel-button" onclick="closeAddModal()">Cancel</button>
                                <button type="submit" class="save-button">Add Appointment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Modal -->
    <div id="declineModal" class="modals">
        <div class="modal-contents">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <h2>Decline Request</h2>
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
// Time slot configurations with both 12-hour and 24-hour formats
const TIME_SLOTS = [
    { display: "09:00 AM", value: "09:00" },
    { display: "10:00 AM", value: "10:00" },
    { display: "11:00 AM", value: "11:00" },
    { value: '12:00', display: '12:00 PM' },
    { display: "01:00 PM", value: "13:00" },
    { display: "02:00 PM", value: "14:00" },
    { display: "03:00 PM", value: "15:00" },
    { display: "04:00 PM", value: "16:00" },
    { display: "05:00 PM", value: "17:00" },
    { display: "06:00 PM", value: "18:00" },
];

// Helper function for consistent date formatting
function formatDateForComparison(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
}

// Toast notification function
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

// Initialize date input constraints
const dateInput = document.getElementById('newDate');
const today = new Date();
const nextYear = new Date(today);
nextYear.setFullYear(today.getFullYear() + 1);

dateInput.min = today.toISOString().split('T')[0];
dateInput.max = nextYear.toISOString().split('T')[0];

// Time conversion functions
function convertTo12Hour(time24) {
    const [hours, minutes] = time24.split(':');
    const hour = parseInt(hours);
    const period = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
    return `${hour12.toString().padStart(2, '0')}:${minutes} ${period}`;
}

function convertTo24Hour(time12) {
    const [time, period] = time12.split(' ');
    let [hours, minutes] = time.split(':');
    hours = parseInt(hours);
    
    if (period === 'PM' && hours !== 12) hours += 12;
    if (period === 'AM' && hours === 12) hours = 0;
    
    return `${hours.toString().padStart(2, '0')}:${minutes}`;
}

// Store IDs globally
let currentTherapistId = null;
const currentPatientId = {{ $appointment->patient_id }};



// Start time change handler - Modified to show all possible end times after the start time
document.getElementById('newStartTime').addEventListener('change', function() {
    const startTime = this.value;
    const endTimeSelect = document.getElementById('newEndTime');
    
    document.getElementById('formattedStartTime').value = startTime;

    if (!startTime) {
        endTimeSelect.innerHTML = '<option value="">Select start time first</option>';
        document.getElementById('formattedEndTime').value = '';
        return;
    }

    const currentIndex = TIME_SLOTS.findIndex(slot => slot.value === startTime);
    if (currentIndex === -1) {
        endTimeSelect.innerHTML = '<option value="">No available end time</option>';
        return;
    }

    // Get all possible end times after the start time
    const availableEndSlots = TIME_SLOTS.slice(currentIndex + 1);
    
    // Update end time options with all available slots
    endTimeSelect.innerHTML = `
        <option value="">Select end time</option>
        ${availableEndSlots.map(slot => `
            <option value="${slot.value}" ${currentIndex + 1 === TIME_SLOTS.indexOf(slot) ? 'selected' : ''}>
                ${slot.display}
            </option>
        `).join('')}
    `;

    // Set default end time (next slot)
    if (availableEndSlots.length > 0) {
        const defaultEndTime = availableEndSlots[0].value;
        endTimeSelect.value = defaultEndTime;
        document.getElementById('formattedEndTime').value = defaultEndTime;
    }
});

// End time change handler - Add validation to ensure end time is after start time
document.getElementById('newEndTime').addEventListener('change', function() {
    const startTime = document.getElementById('formattedStartTime').value;
    const endTime = this.value;

    if (startTime && endTime) {
        if (endTime <= startTime) {
            showToast('End time must be after start time', 'error');
            this.value = ''; // Reset the end time
            document.getElementById('formattedEndTime').value = '';
            return;
        }
    }
    
    document.getElementById('formattedEndTime').value = endTime;
});

// Time slot availability check - Modified to properly check both therapist and patient conflicts
function isTimeSlotAvailable(timeSlot, bookedAppointments, selectedDate) {
    const slotTime = convertTo24Hour(timeSlot.display);
    const formattedSelectedDate = formatDateForComparison(selectedDate);
    
    return !bookedAppointments.some(apt => {
        const aptDate = formatDateForComparison(apt.appointment_details.schedule.date);
        
        // Skip if not on the same date
        if (aptDate !== formattedSelectedDate) return false;
        
        const aptStart = convertTo24Hour(apt.appointment_details.schedule.start);
        const aptEnd = convertTo24Hour(apt.appointment_details.schedule.end);
        
        // Check both therapist and patient conflicts separately
        const isTherapistBooked = apt.participants.therapist.id === currentTherapistId;
        const isPatientBooked = apt.participants.patient.id === currentPatientId;
        
        // If either therapist or patient is booked, the slot should be unavailable
        if (isTherapistBooked || isPatientBooked) {
            // Check if the slot falls within any existing appointment
            return slotTime >= aptStart && slotTime < aptEnd;
        }
        
        return false;
    });
}

// Date change event handler - Modified to show clear messages
dateInput.addEventListener('change', async function() {
    const selectedDate = this.value;
    const timeSlotSelect = document.getElementById('newStartTime');
    const endTimeSelect = document.getElementById('newEndTime');
    
    timeSlotSelect.innerHTML = '<option value="">Select a date first</option>';
    endTimeSelect.innerHTML = '<option value="">Select start time first</option>';
    
    if (!selectedDate) return;

    try {
        const response = await fetch(`/therapist/accepted-appointments?patient_id=${currentPatientId}`);
        const data = await response.json();
        
        currentTherapistId = data.therapist_id;
        
        // Filter available slots
        const availableSlots = TIME_SLOTS.filter(slot => 
            isTimeSlotAvailable(slot, data.appointments || [], selectedDate)
        );

        // Show more specific messages about why slots are unavailable
        if (availableSlots.length === 0) {
            timeSlotSelect.innerHTML = '<option value="">No available slots for this date</option>';
            showToast('No available time slots found. Either therapist or patient has existing appointments.', 'error');
        } else {
            timeSlotSelect.innerHTML = `
                <option value="">Select start time</option>
                ${availableSlots.map(slot => `
                    <option value="${slot.value}">${slot.display}</option>
                `).join('')}
            `;
        }

        timeSlotSelect.disabled = availableSlots.length === 0;
        
        document.getElementById('formattedStartTime').value = '';
        document.getElementById('formattedEndTime').value = '';

    } catch (error) {
        console.error('Error fetching appointments:', error);
        showToast('Error loading time slots', 'error');
    }
});
// Form submission - Enhanced conflict checking
document.getElementById('appointmentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const date = document.getElementById('newDate').value;
    const startTime = document.getElementById('formattedStartTime').value;
    const endTime = document.getElementById('formattedEndTime').value;

    if (!date || !startTime || !endTime) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    try {
        const response = await fetch(`/therapist/accepted-appointments?patient_id=${currentPatientId}`);
        const data = await response.json();
        
        const hasConflict = data.appointments?.some(apt => {
            const aptDate = formatDateForComparison(apt.appointment_details.schedule.date);
            const selectedDateFormatted = formatDateForComparison(date);
            
            if (aptDate !== selectedDateFormatted) return false;
            
            const aptStart = convertTo24Hour(apt.appointment_details.schedule.start);
            const aptEnd = convertTo24Hour(apt.appointment_details.schedule.end);
            
            const isTherapistBooked = apt.participants.therapist.id === currentTherapistId;
            const isPatientBooked = apt.participants.patient.id === currentPatientId;
            
            if (isTherapistBooked || isPatientBooked) {
                const hasOverlap = (startTime < aptEnd && endTime > aptStart);
                if (hasOverlap) {
                    // Show more specific conflict message
                    const conflictParty = isTherapistBooked ? 'Therapist' : 'Patient';
                    showToast(`${conflictParty} already has an appointment at this time`, 'error');
                }
                return hasOverlap;
            }
            
            return false;
        });

        if (!hasConflict) {
            this.submit();
        }
    } catch (error) {
        console.error('Error checking appointment conflicts:', error);
        showToast('Error validating appointment time', 'error');
    }
});

// Modal functions
function openAddModal() {
    document.getElementById("addAppointmentModal").style.display = "block";
}

function closeAddModal() {
    document.getElementById("addAppointmentModal").style.display = "none";
}

function openDeclineModal() {
    document.getElementById("declineModal").style.display = "block";
}

function closeDeclineModal() {
    document.getElementById("declineModal").style.display = "none";
}

// Session storage handling
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

// Outside click handlers
window.onclick = function(event) {
    if (event.target == document.getElementById("declineModal")) {
        closeDeclineModal();
    }
    if (event.target == document.getElementById("addAppointmentModal")) {
        closeAddModal();
    }
}

    </script>

    @if(session('success'))
        <script>showToast("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>showToast("{{ session('error') }}", 'error');</script>
    @endif

</body>
</html>
</x-therapist-layout>
