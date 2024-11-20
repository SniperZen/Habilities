
<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/dash.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            margin: 0;
            text-align: center;
            font-weight: bold;
            color: black;
        }

        .fc-button-primary{
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        .fc .fc-button-group>.fc-button:hover{
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            background-color: #4F4A6E;
        }

        .fc .fc-daygrid-day-frame{
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        .fc .fc-daygrid-day-frame:hover{
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .fc .fc-button-primary:disabled{
            background-color: #AEADD6;
        }
        .fc .fc-button-primary{
            background-color: #4F4A6E;
        }
        .fc .fc-daygrid-day-number, .fc-col-header-cell-cushion {
            color: black !important;
        }
        .fc-today-button {
            display: none;
        }

        .fc-direction-ltr {
            height: 469px;
        }
        .fc .fc-button-group {
            position: relative;
            display: inline-flex;
            gap: 9px;
        }
        .hiddens {
        display: none;
    }
    .unread-indicator {
        width: 10px;
        height: 10px;
        background-color: #8e7cc3;
        border-radius: 50%;
        display: inline-block;
        margin-left: 5px;
    }
    .notification-item.unread {
        background-color: #eed7ff;
        font-weight: bold;
    }

    .notification-item.unread:hover{
        background-color: #f3e1ff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    .notification-timestamp {
        font-size: 0.8em;
        color: #666;
        margin-top: 4px;
        position: relative;
        cursor: pointer;
    }

    </style>
</head>
<body>
        <div class="tcontainer">
        <main class="main-content">
            <header class="welcome-header">
                <div class="ïmg"><img src="{{ asset('images/icons/design.png') }}" alt="Patient Avatar" class="patient-avatar"></div>
                <div class="bod"><h2>Hello, {{ Auth::user()->first_name }}!</h2>
                <p>Today is a new opportunity to inspire hope and foster healing. Let’s continue making a difference, one step at a time.</p><br>
                <div class="but"><a href="AppSched">Check Session  &#8250;</a></div></div>
            </header>
            <div class="actions">
                <div class="action">
                    <a href="{{ route('therapist.AppSched')}}">
                        <button>
                            <img src="{{ asset('images/icons/tinquiry.png') }}" alt="">
                            <p class="p">No. of Inquiries</p>
                            <h6>{{ $inquiriesCount }}</h6>
                        </button>
                    </a>
                </div>
                <div class="action">
                    <a href="{{ route('therapist.AppReq')}}">
                        <button>
                            <img src="{{ asset('images/icons/tappoint.png') }}" alt="">
                            <p class="p">No. of Appointments</p>
                            <h6>{{ $appointmentCount }}</h6>
                        </button>
                    </a>
                </div>
                <div class="action">
                    <a href="{{ route('therapist.profile') }}">
                        <button>
                            <img src="{{ asset('images/icons/tprofile.png') }}" alt="">
                            <p>Patient Count</p>
                            <h6>{{ $patientCount }}</h6>
                        </button>
                    </a>
                </div>
            </div>
            <!-- Habilities' Therapists and Monthly Progress Section -->
            <section class="therapists-progress">
                <!--<div class="therapists">
                    <h3>Habilities' Therapists</h3>
                    <ul>
                        <li>
                            <img src="{{ asset('images/therapist1.png') }}" alt="Maria Lourdes Solita G. Cruz" class="therapist-pic">
                            <div>
                                <strong>Maria Lourdes Solita G. Cruz</strong><br>
                                Occupational Therapist, SpEd Tutorials <span style="color: green;">● Online</span>
                            </div>
                        </li>
                        <li>
                            <img src="{{ asset('images/therapist2.png') }}" alt="Harold B. De Guzman" class="therapist-pic">
                            <div>
                                <strong>Harold B. De Guzman</strong><br>
                                Speech Pathologist <span style="color: red;">● Offline</span>
                            </div>
                        </li>
                        <li>
                            <img src="{{ asset('images/therapist3.png') }}" alt="Angela Reigne Cyril B. Francisco" class="therapist-pic">
                            <div>
                                <strong>Angela Reigne Cyril B. Francisco</strong><br>
                                Occupational Therapist <span style="color: red;">● Offline</span>
                            </div>
                        </li>
                        <li>
                            <img src="{{ asset('images/therapist4.png') }}" alt="Mary Grace G. Sovanaco" class="therapist-pic">
                            <div>
                                <strong>Mary Grace G. Sovanaco</strong><br>
                                Occupational Therapist <span style="color: red;">● Offline</span>
                            </div>
                        </li>
                    </ul>
                </div>-->

                <!--<div class="monthly-progress">
                    <h3>Monthly Progress</h3>
                    <div class="progress-info">
                        <div>
                            <strong>1</strong><br>Upcoming Session
                        </div>
                        <div>
                            <strong>4</strong><br>Sessions Attended
                        </div>
                        <div>
                            <strong>12h</strong><br>Time Spent
                        </div>
                    </div>
                </div>
            </section>-->

           
            <section class="appointments">
    <h3>Scheduled Appointments</h3>
    @if(count($acceptedAppointments) > 0)
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Mode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($acceptedAppointments as $appointment)
                    @if($appointment->status != 'declined')
                        <tr data-id="{{ $appointment->id }}">
                            <td>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                            <td>{{ $appointment->mode }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-appointments">
            <p>No scheduled appointments available at the moment.</p>
        </div>
    @endif
</section>

        </main>

        <aside class="sidebar">
        <div class="notifications card">
            <div class="notifications-header">
                <h3>Notifications</h3>
                <a href="#" id="toggleNotifications" class="see-more">See More ›</a>
            </div>
            
            <div class="notifications-container">
            <ul class="notifications-list" id="notificationsList">
                @foreach(auth()->user()->notifications()->latest()->take(8)->get() as $index => $notification)
                <li class="notification-item {{ $notification->read_at ? '' : 'unread' }} {{ $index >= 4 ? 'hiddens' : '' }}" 
                    data-id="{{ $notification->id }}"
                    data-type="{{ $notification->type }}">
                        @php
                            $userData = null;
                            $title = '';
                            $description = '';

                            if ($notification->type === 'App\Notifications\NewInquiryNotification') {
                                $inquiry = \App\Models\Inquiry::find($notification->data['inquiry_id'] ?? null);
                                $userData = $inquiry ? \App\Models\User::find($inquiry->user_id) : null;
                                $title = ($userData ? $userData->name : 'Unknown User');
                                $description = $notification->data['message'] ?? 'New inquiry submitted.';
                            }
                            elseif ($notification->type === 'App\Notifications\AppointmentCanceledNotificationPatient') {
                                $appointment = \App\Models\Appointment::find($notification->data['appointment_id'] ?? null);
                                $userData = $appointment ? \App\Models\User::find($appointment->patient_id) : null;
                                $title = ($userData ? $userData->name : 'Unknown User');
                                $description = $notification->data['message'] ?? '';
                            } 
                            else {
                                $userData = \App\Models\User::find($notification->data['patient_id'] ?? null);
                                $title = $userData ? $userData->name : 'Unknown Patient';
                                $description = $notification->data['message'] ?? '';
                            }

                        @endphp

                        <img src="{{ $userData && $userData->profile_image ? Storage::url($userData->profile_image) : asset('images/others/default-prof.png') }}" 
                            alt="Profile" class="notification-avatar">

                        <div class="notification-content">
                            <p class="notification-title">{{ $title }}</p>
                            <p class="notification-description">{{ $description }}</p>
                            <p class="notification-timestamp" 
                                title="{{ $notification->created_at->format('F j, Y g:i A') }}" 
                                data-timestamp="{{ $notification->created_at->timestamp }}">
                                    <span class="timestamp-relative">{{ $notification->created_at->diffForHumans() }}</span>
                                    <span class="timestamp-full">{{ $notification->created_at->format('F j, Y g:i A') }}</span>
                                </p>


                            </p>
                        </div>

                        @if(!$notification->read_at)
                            <span class="unread-indicator"></span>
                        @endif
                    </li>
                @endforeach
            </ul>
            </div>
        </div>


            <div class="calendar">
                <div id="calendar"></div>
            </div>
            </aside>
    </div>

    </div>
    <script>
    var appointmentsUrl = "{{ route('get.therapist_appointments') }}";
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var modal = document.getElementById('eventModal');
    var span = document.getElementsByClassName("close2")[0];
    var span2 = document.getElementsByClassName("close2")[0];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: {
            url: appointmentsUrl,
            method: 'GET',
            failure: function() {
                alert('There was an error fetching appointments.');
            }
        },
        eventDisplay: 'block',
        displayEventTime: false,
        eventContent: function(arg) {
            return {
                html: '<div class="custom-event-dot"></div>'
            };
        },
        eventClick: function(info) {
            var event = info.event;
            
            // Format the date and times
            var startDateTime = new Date(event.extendedProps.startTime);
            var endDateTime = new Date(event.extendedProps.endTime);
            
            var date = startDateTime.toISOString().split('T')[0];
            var startTime = startDateTime.toTimeString().split(' ')[0];
            var endTime = endDateTime.toTimeString().split(' ')[0];

            document.getElementById('modalTitle').textContent = 'Event: ' + event.title;
            document.getElementById('modalPatient').textContent = 'Patient: ' + event.extendedProps.patientName;
            
            // Check if there's a note before displaying it
            var noteElement = document.getElementById('modalNote');
            if (event.extendedProps.description && event.extendedProps.description.trim() !== '') {
                noteElement.textContent = 'Note: ' + event.extendedProps.description;
                noteElement.style.display = 'block';
            } else {
                noteElement.style.display = 'none';
            }

            document.getElementById('modalDate').textContent = 'Date: ' + date;
            document.getElementById('modalStartTime').textContent = 'Start: ' + startTime;
            document.getElementById('modalEndTime').textContent = 'End: ' + endTime;
            modal.style.display = "block";
        }
    });

    calendar.render();

    // Close the modal when clicking on <span> (x)
    span.onclick = function() {
        modal.style.display = "none";
    }

    span2.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    fetch(appointmentsUrl)
        .then(response => response.json())
        .then(appointments => {
            const today = new Date();
            const thisWeek = new Date(today.getTime() + (7 * 24 * 60 * 60 * 1000));
            const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
            
            let thisWeekCount = 0;
            let nextMonthCount = 0;
            
            appointments.forEach(appointment => {
                const appointmentDate = new Date(appointment.appointment_date);
                
                if (appointmentDate <= thisWeek) {
                    thisWeekCount++;
                } else if (appointmentDate <= nextMonth) {
                    nextMonthCount++;
                }
            });
            
            let message = '';
            if (thisWeekCount > 0) {
                message = `<p>You have ${thisWeekCount} upcoming session${thisWeekCount > 1 ? 's' : ''} this week. 
                          Please check and confirm your appointment details with your therapist.</p><br>`;
            } else if (nextMonthCount > 0) {
                message = `<p>You have ${nextMonthCount} upcoming session${nextMonthCount > 1 ? 's' : ''} next month. 
                          Please check and confirm your appointment details with your therapist.</p><br>`;
            }
            
            if (message) {
                document.getElementById('upcoming-sessions-notice').innerHTML = message;
            }
        })
        .catch(error => console.error('Error:', error));
});
</script>

<style>
    .custom-event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #8e7cc3;
        margin: 0 auto;
    }
    .fc-daygrid-event {
        background: none !important;
        border: none !important;
    }
</style>
<style>
    /* Existing styles */
    .custom-event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #8e7cc3;
        margin: 0 auto;
    }
    .fc-daygrid-event {
        background: none !important;
        border: none !important;
    }
    .fc-daygrid-event-harness {
        margin-top: 4px !important;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }
    .modal-content {
        display: block;
        background-color: white;
        margin: 15% auto;
        border-radius: 10px;
        width: 450px;
        text-align: center;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

    <!-- Add this somewhere in your blade template, preferably at the end of the body -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <h2 id="modalTitle">Appointment Details</h2>
                    </div>
                    <div class="bot">
                        <p id="modalPatient"></p>
                        <p id="modalNote"></p>
                        <p id="modalDate"></p>
                        <p id="modalStartTime"></p>
                        <p id="modalEndTime"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close2">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const INITIAL_NOTIFICATION_COUNT = 4;
    const toggleButton = document.getElementById('toggleNotifications');
    const notificationsList = document.getElementById('notificationsList');
    let isExpanded = false;

    // Initialize event listeners
    initializeEventListeners();

    function initializeEventListeners() {
        toggleButton.addEventListener('click', handleToggleClick);
        notificationsList.addEventListener('click', handleNotificationClick);
    }

    function handleToggleClick(e) {
        e.preventDefault();
        isExpanded = !isExpanded;
        
        const allItems = notificationsList.querySelectorAll('.notification-item');
        allItems.forEach((item, index) => {
            if (index >= INITIAL_NOTIFICATION_COUNT) {
                item.classList.toggle('hiddens');
            }
        });

        toggleButton.textContent = isExpanded ? 'See Less ‹' : 'See More ›';
    }

    function handleNotificationClick(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem) {
            const notificationId = notificationItem.dataset.id;
            const notificationType = notificationItem.dataset.type;

            markAsReadAndNavigate(notificationId, notificationType);
        }
    }

    async function markAsReadAndNavigate(notificationId, notificationType) {
        try {
            const response = await fetch(`/mark-notification-as-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();
            if (data.success) {
                // Determine the target route based on notification type
                let targetRoute = '';
                
                switch(notificationType) {
                    case 'App\\Notifications\\NewInquiryNotification':
                        targetRoute = '{{ route("therapist.inquiry") }}';
                        break;
                    case 'App\\Notifications\\AppointmentCanceledNotificationPatient':
                        targetRoute = '{{ route("therapist.myHistory") }}';
                        break;
                    case 'App\\Notifications\\AppointmentRequested':
                        targetRoute = '{{ route("therapist.AppReq") }}';
                        break;
                    // Add more cases as needed
                    default:
                        targetRoute = '{{ route("therapist.dash") }}';
                }

                // Navigate to the appropriate page
                window.location.href = targetRoute;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function formatRelativeTime(timestamp) {
        const now = Math.floor(Date.now() / 1000);
        const diff = now - timestamp;
        
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
        if (diff < 7200) return '1 hour ago';
        if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
        if (diff < 172800) return '1 day ago';
        if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
        if (diff < 1209600) return '1 week ago';
        if (diff < 2419200) return Math.floor(diff / 604800) + ' weeks ago';
        if (diff < 4838400) return '1 month ago';
        return Math.floor(diff / 2419200) + ' months ago';
    }

    // Update timestamps
    function updateTimestamps() {
        const timestamps = document.querySelectorAll('.notification-timestamp');
        timestamps.forEach(timestamp => {
            const unix = parseInt(timestamp.dataset.timestamp);
            const relativeSpan = timestamp.querySelector('.timestamp-relative');
            if (relativeSpan) {
                relativeSpan.textContent = formatRelativeTime(unix);
            }
        });
    }

    // Initial timestamp update and interval
    updateTimestamps();
    setInterval(updateTimestamps, 60000);
});

</script>



</body>
</html>
</x-therapist-layout>