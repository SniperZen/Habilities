<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile - Habilities Center for Intervention</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/patient/patient-prof.css') }}">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />

    <!-- Scripts -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

    <!-- Calendar Custom Styles -->
    <style>
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            margin: 0;
            text-align: center;
            font-weight: bold;
            color: black;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="layout">
            <div class="bg">
                <img src="{{ asset('images/others/bg.png') }}" alt="">
            </div>
            <div class="inlayout">
                <main class="main-content">
                    <!-- Profile Section -->
                    <section class="profile-section">
                        <div class="profile-card">
                            @if(Auth::user()->profile_image)
                                <div class="profile-image">
                                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile Picture">
                                </div>
                            @else
                                <p>No profile image set</p>
                            @endif
                            <div class="profile-info">
                                <h2>{{ $user->name }}</h2>
                                <p>Patient ID: P-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                @if($user->account_type === 'child')
                                    <span class="supervised-account">Supervised Account</span>
                                @endif
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="{{ route('patient.edit-profile') }}" class="button-link">Edit Profile</a>
                        </div>
                    </section>

                    <!-- About Me Section -->
                    <div class="bod">
                        <div class="about-section">
                            <h3>About Me!</h3>
                            <ul>
                                <li><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.39999 19.5128C1.39999 15.7373 4.55428 12.6766 11 12.6766C17.4457 12.6766 20.6 15.7373 20.6 19.5128C20.6 20.1135 20.1618 20.6004 19.6212 20.6004H2.37882C1.83823 20.6004 1.39999 20.1135 1.39999 19.5128Z" fill="#395886"/>
                                        <path d="M14.6 5.00039C14.6 6.98862 12.9882 8.60039 11 8.60039C9.01177 8.60039 7.39999 6.98862 7.39999 5.00039C7.39999 3.01217 9.01177 1.40039 11 1.40039C12.9882 1.40039 14.6 3.01217 14.6 5.00039Z" fill="#395886"/>
                                        <path d="M1.39999 19.5128C1.39999 15.7373 4.55428 12.6766 11 12.6766C17.4457 12.6766 20.6 15.7373 20.6 19.5128C20.6 20.1135 20.1618 20.6004 19.6212 20.6004H2.37882C1.83823 20.6004 1.39999 20.1135 1.39999 19.5128Z" stroke="#395886" stroke-width="2"/>
                                        <path d="M14.6 5.00039C14.6 6.98862 12.9882 8.60039 11 8.60039C9.01177 8.60039 7.39999 6.98862 7.39999 5.00039C7.39999 3.01217 9.01177 1.40039 11 1.40039C12.9882 1.40039 14.6 3.01217 14.6 5.00039Z" stroke="#395886" stroke-width="2"/>
                                    </svg> {{ $user->gender }}</li>
                                <li><svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 6.43239C11.2333 6.43239 12.2222 5.46753 12.2222 4.28826C12.2222 3.88087 12.1111 3.50565 11.9 3.18403L10 0L8.1 3.18403C7.88889 3.50565 7.77778 3.88087 7.77778 4.28826C7.77778 5.46753 8.77778 6.43239 10 6.43239ZM15.1111 17.1423L13.9222 15.9952L12.7222 17.1423C11.2778 18.536 8.74445 18.5467 7.28889 17.1423L6.1 15.9952L4.88889 17.1423C4.16667 17.8391 3.2 18.2251 2.17778 18.2251C1.36667 18.2251 0.622222 17.9785 0 17.5711V22.5133C0 23.103 0.5 23.5854 1.11111 23.5854H18.8889C19.5 23.5854 20 23.103 20 22.5133V17.5711C19.3778 17.9785 18.6333 18.2251 17.8222 18.2251C16.8 18.2251 15.8333 17.8391 15.1111 17.1423ZM16.6667 9.64858H11.1111V7.50445H8.88889V9.64858H3.33333C1.48889 9.64858 0 11.0851 0 12.8648V14.5157C0 15.6736 0.977778 16.617 2.17778 16.617C2.75556 16.617 3.31111 16.4026 3.71111 16.0059L6.08889 13.7224L8.45556 16.0059C9.27778 16.7992 10.7111 16.7992 11.5333 16.0059L13.9111 13.7224L16.2778 16.0059C16.6889 16.4026 17.2333 16.617 17.8111 16.617C19.0111 16.617 19.9889 15.6736 19.9889 14.5157V12.8648C20 11.0851 18.5111 9.64858 16.6667 9.64858Z" fill="#395886"/>
                                    </svg> {{ $user->date_of_birth->format('F j, Y') }}</li>
                                <li><svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 8.17407C0 3.65367 3.84388 0 8.49345 0C13.1561 0 17 3.65367 17 8.17407C17 10.452 16.157 12.5667 14.7695 14.3592C13.2388 16.3364 11.3522 18.059 9.22854 19.4112C8.74251 19.7237 8.30387 19.7473 7.77045 19.4112C5.63474 18.059 3.74809 16.3364 2.2305 14.3592C0.841983 12.5667 0 10.452 0 8.17407ZM5.69434 8.42856C5.69434 9.94289 6.95177 11.1339 8.49355 11.1339C10.0364 11.1339 11.3059 9.94289 11.3059 8.42856C11.3059 6.92601 10.0364 5.677 8.49355 5.677C6.95177 5.677 5.69434 6.92601 5.69434 8.42856Z" fill="#395886"/>
                                    </svg> {{ $user->home_address }}</li>
                                <li><svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1798 0C18.722 0 20.2055 0.578716 21.2968 1.61713C22.3893 2.65336 23 4.05101 23 5.51418V14.1403C23 17.1868 20.3895 19.6545 17.1798 19.6545H5.819C2.60935 19.6545 0 17.1868 0 14.1403V5.51418C0 2.46773 2.59785 0 5.819 0H17.1798ZM19.0087 7.14097L19.1007 7.05361C19.3755 6.73696 19.3755 6.27835 19.088 5.96169C18.9282 5.799 18.7085 5.69963 18.4797 5.6778C18.2382 5.66578 18.0082 5.74331 17.8345 5.89618L12.6492 9.82708C11.9822 10.3523 11.0265 10.3523 10.3492 9.82708L5.17417 5.89618C4.81652 5.64504 4.32202 5.6778 4.02417 5.97261C3.71367 6.26743 3.67917 6.73696 3.94252 7.06453L4.09317 7.20648L9.32567 11.0828C9.96967 11.5632 10.7505 11.8253 11.5682 11.8253C12.3835 11.8253 13.1782 11.5632 13.821 11.0828L19.0087 7.14097Z" fill="#395886"/>
                                </svg> {{ $user->email }}</li>
                                <li><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.6633 16.7705C18.6633 16.7705 17.5047 17.9085 17.2207 18.2422C16.7582 18.7358 16.2132 18.9689 15.4988 18.9689C15.4301 18.9689 15.3568 18.9689 15.2881 18.9643C13.9279 18.8775 12.6639 18.3473 11.7159 17.8948C9.12383 16.6426 6.84772 14.8647 4.95631 12.6115C3.39463 10.7331 2.35046 8.99638 1.65892 7.13168C1.23301 5.99366 1.0773 5.10702 1.146 4.27064C1.19179 3.73591 1.39788 3.29259 1.778 2.91325L3.33967 1.35477C3.56408 1.14453 3.80222 1.03027 4.03579 1.03027C4.32431 1.03027 4.55787 1.20395 4.70442 1.3502C4.709 1.35477 4.71358 1.35934 4.71816 1.36391C4.99752 1.62442 5.26315 1.89407 5.54251 2.182C5.68448 2.32825 5.83103 2.4745 5.97758 2.62532L7.22784 3.87303C7.71328 4.35748 7.71328 4.80538 7.22784 5.28983C7.09503 5.42237 6.96679 5.55491 6.83398 5.68288C6.44929 6.07593 6.75149 5.77435 6.35305 6.13083C6.34389 6.13997 6.33473 6.14454 6.33015 6.15368C5.9363 6.54673 6.00958 6.93064 6.09201 7.19115C6.09659 7.20486 6.10117 7.21857 6.10575 7.23228C6.43091 8.01838 6.88888 8.75878 7.58499 9.64085L7.58957 9.64542C8.85357 11.1993 10.1863 12.4105 11.6563 13.3383C11.8441 13.4571 12.0365 13.5531 12.2196 13.6445C12.3845 13.7267 12.5402 13.8044 12.673 13.8867C12.6914 13.8958 12.7097 13.9095 12.728 13.9187C12.8837 13.9964 13.0303 14.033 13.1814 14.033C13.5615 14.033 13.7996 13.7953 13.8775 13.7176L14.7752 12.8217C14.9309 12.6664 15.1782 12.479 15.4667 12.479C15.7507 12.479 15.9842 12.6572 16.1262 12.8126C16.1308 12.8172 16.1308 12.8172 16.1354 12.8217L18.6588 15.34C19.1305 15.8062 18.6633 16.7705 18.6633 16.7705Z" fill="#395886" stroke="#395886" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg> {{ $user->contact_number }}</li>
                            </ul>
                        </div>

                        <!-- Messages Section -->
                        <section class="messages-section">
                        @if($feedback->count() > 0)
    @foreach($feedback as $item)
        <div class="message" onclick="openModal('modal-{{ $item->id }}')">
            <div class="message-header">
                @if($item->sender->profile_image)
                    <img src="{{ Storage::url($item->sender->profile_image) }}" alt="{{ $item->sender->name }}'s Profile Picture" class="therapist-pic">
                @else
                    <img src="{{ asset('images/therapist-default.png') }}" alt="Default Therapist Picture" class="therapist-pic">
                @endif
                <div>
                    <h4>{{ $item->sender->name }}</h4>
                    <span>{{ $item->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <h3>{{ $item->title }}</h3>
            <p>{!! Str::words(strip_tags($item->content), 100, '...') !!}</p>
        </div>

        <!-- Modal for full content -->
        <div id="modal-{{ $item->id }}" class="modal2" style="display: none;">
            <div class="modal-content">
                <!-- <span class="close" onclick="closeModal('modal-{{ $item->id }}')">&times;</span> -->
                <div class="head_template">
                    <div class="headers">
                        <div class="logos">
                            <img src="{{ asset('images/logo.png') }}" alt="Habilities Center for Intervention Logo">
                        </div>
                        <div class="info">
                            <h2 style="margin-bottom: 0;">Habilities Center for Intervention</h2>
                            <p>112 Sampaguita Street, Phase 1<br>
                            Brgy. Bulihan, City of Malolos<br>
                            0927 307 0434</p>
                        </div>
                    </div>

                    <div class="title">
                        <h1>Occupational Therapy Feedback</h1>
                    </div>

                    <div class="note">
                        <p>NOTE: HIGHLY CONFIDENTIAL. UPLOADING, SHARING, OR DUPLICATION OF THIS DOCUMENT WITHOUT CONSENT OF THE THERAPIST IS HIGHLY PROHIBITED.</p>
                    </div>
                </div>
                <h2>{{ $item->title }}</h2>
                <p>{!! strip_tags($item->content) !!}</p>
                <div class="close3"><button class="close5" onclick="closeModal('modal-{{ $item->id }}')">Close</button></div>
            </div>
        </div>
    @endforeach
@else
    <div class="no-feedback">
        <p>No feedback available from therapist at this time.</p>
    </div>
@endif
</section>

                        </section>

                        <script>
                        function openModal(modalId) {
                            document.getElementById(modalId).style.display = "block";
                        }

                        function closeModal(modalId) {
                            document.getElementById(modalId).style.display = "none";
                        }

                        // Close modal when clicking outside the modal-content
                        window.onclick = function(event) {
                            if (event.target.classList.contains('modal2')) {
                                event.target.style.display = "none";
                            }
                        }
                        </script>
                    </div>
                </main>

                <!-- Right Sidebar -->
                <aside class="right-sidebar">
                    <!-- Upcoming Session Section -->
                    <div class="upcoming">
                        <h3>Upcoming</h3>
                        <div class="upcoming-session" id="upcomingSession">
                            <p>Loading...</p>
                        </div>
                    </div>



                    <!-- Calendar -->
                    <div class="calendar">
                        <div id="calendar"></div>
                        <script>
                            var appointmentsUrl = "{{ route('get.appointments') }}";
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var calendarEl = document.getElementById('calendar');
                                var modal = document.getElementById('eventModal');
                                var span = document.getElementsByClassName("close4")[0];
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
                                        var startDateTime = new Date(event.extendedProps.startTime);
                                        var endDateTime = new Date(event.extendedProps.endTime);
                                        var date = startDateTime.toISOString().split('T')[0];
                                        var startTime = startDateTime.toTimeString().split(' ')[0];
                                        var endTime = endDateTime.toTimeString().split(' ')[0];

                                        document.getElementById('modalTitle').textContent = 'Event: ' + event.title;
                                        document.getElementById('modalPatient').textContent = 'Therapist: ' + event.extendedProps.patientName;

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

                                span.onclick = function() {
                                    modal.style.display = "none";
                                }

                                span2.onclick = function() {
                                    modal.style.display = "none";
                                }


                                window.onclick = function(event) {
                                    if (event.target == modal) {
                                        modal.style.display = "none";
                                    }
                                }
                            });
                        </script>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            fetch(appointmentsUrl)
                                .then(response => response.json())
                                .then(events => {
                                    const upcomingSessionDiv = document.getElementById('upcomingSession');
                                    
                                    // Filter future events and sort by date
                                    const now = new Date();
                                    const futureEvents = events
                                        .filter(event => new Date(event.start) >= now)
                                        .sort((a, b) => new Date(a.start) - new Date(b.start));

                                    if (futureEvents.length > 0) {
                                        // Get the next upcoming event
                                        const nextEvent = futureEvents[0];
                                        const startTime = new Date(nextEvent.extendedProps.startTime).toLocaleTimeString('en-US', {
                                            hour: 'numeric',
                                            minute: '2-digit',
                                            hour12: true
                                        });
                                        
                                        // Format the date
                                        const appointmentDate = new Date(nextEvent.start).toLocaleDateString('en-US', {
                                            month: 'long',
                                            day: 'numeric',
                                            year: 'numeric'
                                        });

                                        // Capitalize first letter of the mode
                                        const capitalizedMode = nextEvent.title.charAt(0).toUpperCase() + nextEvent.title.slice(1);

                                        upcomingSessionDiv.innerHTML = `
                                            <p>${capitalizedMode} with Teacher ${nextEvent.extendedProps.patientName}</p>
                                            <p>${appointmentDate} | ${startTime}</p>
                                            ${nextEvent.extendedProps.description ? `<p>Note: ${nextEvent.extendedProps.description}</p>` : ''}
                                        `;
                                    } else {
                                        upcomingSessionDiv.innerHTML = '<p>No upcoming sessions scheduled</p>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching appointments:', error);
                                    document.getElementById('upcomingSession').innerHTML = '<p>Error loading appointments</p>';
                                });
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
                            .modal-content {
                                background-color: #fefefe;
                                margin: 6% auto;
                                padding: 28px;
                                border: 1px solid #888;
                                width: 80%;
                                max-width: 600px;
                                border-radius: 10px;
                            }
                            .modal {
                                display: none;
                                position: fixed;
                                z-index: 1000000;
                                left: 0;
                                top: 0;
                                width: 100%;
                                height: 100%;
                                overflow: auto;
                                background-color: rgba(0,0,0,0.4);
                            }

                            .modal-content h2{
                                margin-bottom: 19px;
                            }
                            .close {
                                color: #aaa;
                                float: right;
                                font-size: 28px;
                                font-weight: bold;
                                cursor: pointer;
                            }
                            .close:hover, .close:focus {
                                color: black;
                                text-decoration: none;
                                cursor: pointer;
                            }
                            .fc .fc-button-group {
                                position: relative;
                                display: inline-flex;
                                gap: 9px;
                            }
                        </style>

                        <!-- Modal for Event Details -->
                        <div id="eventModal" class="modal">
                            <div class="modal-content">
                                <span class="close4">&times;</span>
                                <h2 id="modalTitle"></h2>
                                <p id="modalPatient"></p>
                                <p id="modalNote"></p>
                                <p id="modalDate"></p>
                                <p id="modalStartTime"></p>
                                <p id="modalEndTime"></p>
                                <div class="close3"><button class="close2">Close</button></div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</body>
</html>
</x-patient-layout>
