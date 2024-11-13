<!-- <x-patient-layout> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/patient.css')}}">
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

        .fc .fc-daygrid-day-frame{
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        .fc .fc-daygrid-day-frame:hover{
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .fc .fc-button-group>.fc-button:hover{
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            background-color: #395886;
        }
        .fc .fc-button-primary:disabled {
            background-color: #c0d3f1;
            color: black;
        }
        .fc .fc-button-primary {
            background-color: #395886;
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
        .notification-timestamp {
            font-size: 0.75rem;
            color: #666;
            display: block;
            margin-top: 4px;
        }

        .timestamp-full {
            display: none;
            position: absolute;
            background: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            white-space: nowrap;
        }

        .notification-timestamp:hover .timestamp-full {
            display: block;
        }
        .notifications-container {
        max-height: 324px;
        overflow-y: auto;
        overflow-x: hidden;
        transition: max-height 0.3s ease;
    }

    .fc-button-primary{
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        .fc .fc-button-group>.fc-button:hover{
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            background-color: #395886;
        }

    .notifications-container.expanded {
        max-height: 600px; 
    }

    .notifications-list {
        padding: 0;
        margin: 0;
        list-style-type: none;
    }

    .notifications-container::-webkit-scrollbar {
        width: 8px;
    }

    .notifications-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notifications-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .notifications-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    </style>
</head>
<body>
        <div class="tcontainer">
        <main class="main-content">
            <header class="welcome-header">
                <div class="ïmg"><img class="patient-avatar" src="{{ asset('images/icons/design.png') }}" alt="Patient Avatar" ></div>
                <div class="bod"><h2>Hello, {{ Auth::user()->first_name }}!</h2>
                <p>Today is a new opportunity to take positive steps, care for yourself, and embrace the support that helps you grow</p><br>
                <div class="but"><a href="{{ route('patient.appntmnt') }}">Check Session  &#8250;</a></div></div>
            </header>
            <div class="actions">
                <a href="{{route('patient.inquiry')}}"><button> <div class="circle"><svg width="65" height="65" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="22.5" cy="22.5" r="22.5" fill="#395886"/><path d="M21.3269 32H11.75C11.1977 32 10.75 31.5523 10.75 31V16C10.75 13.7909 12.5409 12 14.75 12H29.25C31.4591 12 33.25 13.7909 33.25 16V21.2308" stroke="#F0F3FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.75 24.5H19.5" stroke="#F0F3FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.75 19.5H23.25" stroke="#F0F3FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="27.625" cy="26.375" r="3.375" stroke="#F0F3FA" stroke-width="2"/><path d="M30.125 28.875L33.875 32.625" stroke="#F0F3FA" stroke-width="2" stroke-linecap="round"/></svg></div><p class="p">Make an <br> Inquiry</p></button></a>
                <a href="{{ route('patient.AppReq')}}"><button> <div class="circle"><svg width="65" height="65" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="22.5" cy="22.5" r="22.5" fill="#395886"/><path d="M26.6665 11.667C28.5391 11.667 29.4754 11.667 30.148 12.1164C30.4392 12.311 30.6892 12.561 30.8838 12.8521C31.3332 13.5247 31.3332 14.461 31.3332 16.3337V29.0003C31.3332 31.5145 31.3332 32.7716 30.5521 33.5526C29.7711 34.3337 28.514 34.3337 25.9998 34.3337H17.9998C15.4857 34.3337 14.2286 34.3337 13.4476 33.5526C12.6665 32.7716 12.6665 31.5145 12.6665 29.0003V16.3337C12.6665 14.461 12.6665 13.5247 13.1159 12.8521C13.3105 12.561 13.5605 12.311 13.8516 12.1164C14.5242 11.667 15.4606 11.667 17.3332 11.667" stroke="#F0F3FA" stroke-width="2"/><path d="M18 11.6667C18 10.1939 19.1939 9 20.6667 9H23.3333C24.8061 9 26 10.1939 26 11.6667C26 13.1394 24.8061 14.3333 23.3333 14.3333H20.6667C19.1939 14.3333 18 13.1394 18 11.6667Z" stroke="#F0F3FA" stroke-width="2"/><path d="M18 21L26 21" stroke="#F0F3FA" stroke-width="2" stroke-linecap="round"/><path d="M18 26.333L23.3333 26.333" stroke="#F0F3FA" stroke-width="2" stroke-linecap="round"/></svg></div><p class="p">Reserve an <br> Appointment</p></button></a>
                <a href="{{ route('patient.profile') }}"><button> <div class="circle"><svg width="65" height="65" viewBox="0 0 47 47" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="23.4998" cy="23.5004" r="22.5" transform="rotate(1.59243 23.4998 23.5004)" fill="#395886"/><rect width="27" height="27" transform="translate(10.3804 9.63086) rotate(1.59243)" fill="#395886"/><path d="M12.4381 32.7739C12.5562 28.528 16.1991 25.1847 23.4477 25.3862C30.6963 25.5877 34.1478 29.1283 34.0298 33.3741C34.011 34.0496 33.503 34.5835 32.895 34.5666L13.5049 34.0275C12.897 34.0106 12.4194 33.4493 12.4381 32.7739Z" stroke="#F0F3FA" stroke-width="2"/><path d="M27.7361 16.8664C27.674 19.1023 25.811 20.8644 23.5751 20.8023C21.3392 20.7401 19.5771 18.8772 19.6392 16.6413C19.7014 14.4054 21.5643 12.6432 23.8002 12.7054C26.0361 12.7676 27.7983 14.6305 27.7361 16.8664Z" stroke="#F0F3FA" stroke-width="2"/></svg></div><p class="p">Go to <br> My Profile</p></button></a>
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
                <h3>Recent Therapy Sessions</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Therapist Name</th>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($appointments->count() > 0)
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->therapist->name ?? '' }}</td>
                                <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') : '-' }}</td>
                                <td>{{ ucfirst($appointment->mode) }}</td>
                                <td>{{ ucfirst($appointment->status) }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center" style="text-align: center; padding: 20px;">
                                    <div class="alert alert-info" role="alert">
                                        No appointments found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </section>
        </main>

        <aside class="sidebarr" style="border-right:none;">
        <div class="notifications card">
    <div class="notifications-header">
        <h3>Notifications</h3>
        <a href="#" id="toggleNotifications" class="see-more">See More ›</a>
    </div>

    <div class="notifications-container">
    <ul class="notifications-list" id="notificationsList">
        @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $index => $notification)
                @php
                    $profile_image = asset('images/others/default-prof.png'); // Default image
                    $sender_name = 'Unknown';
                    
                    if ($notification->type === 'App\Notifications\TherapistFeedbackNotification') {
                        $feedback = \App\Models\Feedback::find($notification->data['feedback_id']);
                        $sender = $feedback ? $feedback->sender : null;
                    } elseif (in_array($notification->type, [
                        'App\Notifications\AcceptedNotification',
                        'App\Notifications\AppointmentCanceledNotification',
                        'App\Notifications\AppointmentUpdatedNotification',
                        'App\Notifications\AppointmentFinishedNotification' // Add this line
                    ])) {
                        $appointment = \App\Models\Appointment::find($notification->data['appointment_id']);
                        $sender = $appointment ? $appointment->therapist : null;
                    } else {
                        $sender_id = $notification->data['sender_id'] ?? null;
                        $sender = $sender_id ? \App\Models\User::find($sender_id) : null;
                    }
                    
                    if ($sender) {
                        $sender_name = $sender->name;
                        $profile_image = $sender->profile_image 
                            ? Storage::url($sender->profile_image) 
                            : $profile_image;
                    }
                @endphp
                <li class="notification-item {{ $notification->read_at ? '' : 'unread' }} {{ $index >= 4 ? 'hiddens' : '' }}" 
                    data-id="{{ $notification->id }}"
                    data-type="{{ $notification->type }}">
                    <img src="{{ $profile_image }}" alt="Sender Avatar" class="notification-avatar">
                    <div class="notification-content">
                        <p class="notification-title">
                            @if($notification->type === 'App\Notifications\TherapistFeedbackNotification')
                                {{ $sender_name }}
                            @elseif($notification->type === 'App\Notifications\AcceptedNotification')
                                {{ $sender_name }}
                            @elseif($notification->type === 'App\Notifications\AppointmentCanceledNotification')
                                {{ $sender_name }}
                            @elseif($notification->type === 'App\Notifications\AppointmentUpdatedNotification')
                                {{ $sender_name }}
                            @elseif($notification->type === 'App\Notifications\AppointmentFinishedNotification')
                                {{ $sender_name }}
                            @else
                                sent a Notification
                            @endif
                        </p>
                        <p class="notification-description">{{ $notification->data['message'] ?? 'No message' }}</p>
                        <span class="notification-timestamp">
                            {{ $notification->created_at->diffForHumans() }}
                            <span class="timestamp-full">
                                {{ $notification->created_at->format('F j, Y g:i A') }}
                            </span>
                        </span>
                    </div>
                    @if($notification->read_at === null)
                        <span class="unread-indicator"></span>
                    @endif
                </li>
        @empty
            <li class="no-notifications">No notifications</li>
        @endforelse
    </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleNotifications');
    const notificationsList = document.getElementById('notificationsList');
    let isExpanded = false;

    toggleButton.addEventListener('click', function(e) {
        e.preventDefault();
        isExpanded = !isExpanded;
        
        const allItems = notificationsList.querySelectorAll('.notification-item');
        allItems.forEach((item, index) => {
            if (index >= 4) {
                item.classList.toggle('hiddens');
            }
        });

        toggleButton.textContent = isExpanded ? 'See Less ‹' : 'See More ›';
    });

    // Updated notification click handler with navigation
    notificationsList.addEventListener('click', function(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem) {
            const notificationId = notificationItem.dataset.id;
            const notificationType = notificationItem.dataset.type;

            // Mark as read
            fetch(`/mark-notification-as-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationItem.classList.remove('unread');
                    const unreadIndicator = notificationItem.querySelector('.unread-indicator');
                    if (unreadIndicator) {
                        unreadIndicator.remove();
                    }

                        // Handle navigation based on notification type
                        if (notificationType === 'App\\Notifications\\AcceptedNotification' ||
                            notificationType === 'App\\Notifications\\AppointmentUpdatedNotification') {
                            window.location.href = '{{ route("patient.appntmnt") }}';
                        } else if (notificationType === 'App\\Notifications\\AppointmentCanceledNotification' ||
                                notificationType === 'App\\Notifications\\AppointmentFinishedNotification') { // Add this condition
                            window.location.href = '{{ route("patient.myHistory") }}';
                        } else if (notificationType === 'App\\Notifications\\TherapistFeedbackNotification') {
                            window.location.href = '{{ route("patient.profile") }}';
                        }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>


<style>
.hiddens {
    display: none;
}
.unread-indicator {
    width: 10px;
    height: 10px;
    background-color: #007bff;
    border-radius: 50%;
    display: inline-block;
    margin-left: 5px;
}
.notification-item.unread {
    background-color: #f0f8ff;
    font-weight: bold;
}
.notification-item.unread:hover{
    background-color: #c5dcfc;
}
</style>

            <div class="calendar">
                <div id="calendar"></div>
            </div>
            </aside>
    </div>

    </div>
    <script>
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var appointmentsUrl = "{{ route('get.appointments') }}";  // Generate route outside script
    var calendarEl = document.getElementById('calendar');
    var modal = document.getElementById('eventModal');
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
            document.getElementById('modalPatient').textContent = 'Therapist: ' + event.extendedProps.patientName;
            
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

    span2.onclick = function() {
        modal.style.display = "none";
    }
});

</script>
<style>
    .custom-event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #3788d8;
        margin: 0 auto;
    }
    .fc-daygrid-event {
        background: none !important;
        border: none !important;
    }
    /* Existing styles */
    .custom-event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #3788d8;
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

<div id="eventModal" class="modal">
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2 id="modalTitle"></h2>
                </div>
                <div class="bot">
                    <p id="modalPatient"></p>
                    <p id="modalNote"></p>
                    <p id="modalDate"></p>
                    <p id="modalStartTime"></p>
                    <p id="modalEndTime"></p>
                </div>
            </div>
            <div class="close3"><button class="close2">Close</button></div>
        </div>
    </div>
</div>
</body>
</html>
<!-- 
</x-patient-layout> -->
