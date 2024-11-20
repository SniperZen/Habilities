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
                                <div id="patientList" class="patient-list-dropdown">
                                    <!-- Patients will be populated here -->
                                </div>
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
                            <label for="appointmentDate">Date<span style="color: red;">*</span></label>
                            <input type="date" id="appointmentDate" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="newStartTime">Start Time<span style="color: red;">*</span></label>
                            <input type="time" id="newStartTime" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="newEndTime">End Time<span style="color: red;">*</span></label>
                            <input type="time" id="newEndTime" name="end_time" required>
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
$(document).ready(function() {
    const patientSearch = $('#patientSearch');
    const patientList = $('#patientList');
    const patientIdInput = $('#patient_id');

    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    patientSearch.on('focus', function() {
        $.ajax({
            url: '/search-users', // Use your existing route
            method: 'GET',
            data: {
                query: patientSearch.val()
            },
            success: function(response) {
                let html = '';
                if (response.length > 0) {
                    response.forEach(user => {
                        html += `<div class="patient-item" data-id="${user.id}">${user.name}</div>`;
                    });
                } else {
                    html = '<div class="patient-item">No patients found</div>';
                }
                patientList.html(html);
                patientList.show();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                patientList.html('<div class="patient-item">Error loading patients</div>');
                patientList.show();
            }
        });
    });

    // Handle patient selection
    $(document).on('click', '.patient-item', function() {
        const patientId = $(this).data('id');
        const patientName = $(this).text();
        patientIdInput.val(patientId);
        patientSearch.val(patientName);
        patientList.hide();
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            patientList.hide();
        }
    });

    // Real-time search as user types
    patientSearch.on('input', function() {
        if ($(this).val().length > 0) {
            $.ajax({
                url: '/search-users',
                method: 'GET',
                data: {
                    query: $(this).val()
                },
                success: function(response) {
                    let html = '';
                    if (response.length > 0) {
                        response.forEach(user => {
                            html += `<div class="patient-item" data-id="${user.id}">${user.name}</div>`;
                        });
                    } else {
                        html = '<div class="patient-item">No patients found</div>';
                    }
                    patientList.html(html);
                    patientList.show();
                }
            });
        } else {
            patientList.hide();
        }
    });
});
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
