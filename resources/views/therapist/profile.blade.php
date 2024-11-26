<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile - Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/profile.css')}}">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <style>
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            margin: 0;
            text-align: center;
            font-weight: bold;
            color: black;
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

        .fc .fc-daygrid-day-frame{
            box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        .fc .fc-daygrid-day-frame:hover{
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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

        .fc-direction-ltr {
            height: 469px;
        }
        .fc .fc-button-group {
            position: relative;
            display: inline-flex;
            gap: 9px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="layout">
    <div class="bg"><img src="{{ asset('images/others/bg.png') }}" alt=""></div>
    <div class="inlayout">
        <main class="main-content">
            <section class="profile-section">
                <div class="profile-card">
                    @if(Auth::user()->profile_image)
                        <div class="profile-image">
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                alt="Profile Picture">
                        </div>
                    @else
                        <p>No profile image set</p>
                    @endif
                    <div class="profile-info">
                        <h2>{{ $user->name }}</h2>
                        <p>Therapist ID: T-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="profile-actions">
                <a href="{{route('therapist.edit')}}" class="button-link">Edit Profile</a>
            </div>
                </section>

                <div class="bod">
                <div class="about-section">
                    <h3>About Me!</h3>
                    <ul>
                    <li><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.39999 19.5128C1.39999 15.7373 4.55428 12.6766 11 12.6766C17.4457 12.6766 20.6 15.7373 20.6 19.5128C20.6 20.1135 20.1618 20.6004 19.6212 20.6004H2.37882C1.83823 20.6004 1.39999 20.1135 1.39999 19.5128Z" fill="#4F4A6E"/>
                            <path d="M14.6 5.00039C14.6 6.98862 12.9882 8.60039 11 8.60039C9.01177 8.60039 7.39999 6.98862 7.39999 5.00039C7.39999 3.01217 9.01177 1.40039 11 1.40039C12.9882 1.40039 14.6 3.01217 14.6 5.00039Z" fill="#4F4A6E"/>
                            <path d="M1.39999 19.5128C1.39999 15.7373 4.55428 12.6766 11 12.6766C17.4457 12.6766 20.6 15.7373 20.6 19.5128C20.6 20.1135 20.1618 20.6004 19.6212 20.6004H2.37882C1.83823 20.6004 1.39999 20.1135 1.39999 19.5128Z" stroke="#4F4A6E" stroke-width="2"/>
                            <path d="M14.6 5.00039C14.6 6.98862 12.9882 8.60039 11 8.60039C9.01177 8.60039 7.39999 6.98862 7.39999 5.00039C7.39999 3.01217 9.01177 1.40039 11 1.40039C12.9882 1.40039 14.6 3.01217 14.6 5.00039Z" stroke="#4F4A6E" stroke-width="2"/>
                        </svg> {{ $user->gender }}
                    </li>
                    <li><svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6.43239C11.2333 6.43239 12.2222 5.46753 12.2222 4.28826C12.2222 3.88087 12.1111 3.50565 11.9 3.18403L10 0L8.1 3.18403C7.88889 3.50565 7.77778 3.88087 7.77778 4.28826C7.77778 5.46753 8.77778 6.43239 10 6.43239ZM15.1111 17.1423L13.9222 15.9952L12.7222 17.1423C11.2778 18.536 8.74445 18.5467 7.28889 17.1423L6.1 15.9952L4.88889 17.1423C4.16667 17.8391 3.2 18.2251 2.17778 18.2251C1.36667 18.2251 0.622222 17.9785 0 17.5711V22.5133C0 23.103 0.5 23.5854 1.11111 23.5854H18.8889C19.5 23.5854 20 23.103 20 22.5133V17.5711C19.3778 17.9785 18.6333 18.2251 17.8222 18.2251C16.8 18.2251 15.8333 17.8391 15.1111 17.1423ZM16.6667 9.64858H11.1111V7.50445H8.88889V9.64858H3.33333C1.48889 9.64858 0 11.0851 0 12.8648V14.5157C0 15.6736 0.977778 16.617 2.17778 16.617C2.75556 16.617 3.31111 16.4026 3.71111 16.0059L6.08889 13.7224L8.45556 16.0059C9.27778 16.7992 10.7111 16.7992 11.5333 16.0059L13.9111 13.7224L16.2778 16.0059C16.6889 16.4026 17.2333 16.617 17.8111 16.617C19.0111 16.617 19.9889 15.6736 19.9889 14.5157V12.8648C20 11.0851 18.5111 9.64858 16.6667 9.64858Z" fill="#4F4A6E"/>
                        </svg>{{ $user->date_of_birth->format('F j, Y') }}
                    </li>
                    <li><svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 8.17407C0 3.65367 3.84388 0 8.49345 0C13.1561 0 17 3.65367 17 8.17407C17 10.452 16.157 12.5667 14.7695 14.3592C13.2388 16.3364 11.3522 18.059 9.22854 19.4112C8.74251 19.7237 8.30387 19.7473 7.77045 19.4112C5.63474 18.059 3.74809 16.3364 2.2305 14.3592C0.841983 12.5667 0 10.452 0 8.17407ZM5.69434 8.42856C5.69434 9.94289 6.95177 11.1339 8.49355 11.1339C10.0364 11.1339 11.3059 9.94289 11.3059 8.42856C11.3059 6.92601 10.0364 5.677 8.49355 5.677C6.95177 5.677 5.69434 6.92601 5.69434 8.42856Z" fill="#4F4A6E"/>
                        </svg>{{ $user->home_address }}
                    </li>
                    <li><svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1798 0C18.722 0 20.2055 0.578716 21.2968 1.61713C22.3893 2.65336 23 4.05101 23 5.51418V14.1403C23 17.1868 20.3895 19.6545 17.1798 19.6545H5.819C2.60935 19.6545 0 17.1868 0 14.1403V5.51418C0 2.46773 2.59785 0 5.819 0H17.1798ZM19.0087 7.14097L19.1007 7.05361C19.3755 6.73696 19.3755 6.27835 19.088 5.96169C18.9282 5.799 18.7085 5.69963 18.4797 5.6778C18.2382 5.66578 18.0082 5.74331 17.8345 5.89618L12.6492 9.82708C11.9822 10.3523 11.0265 10.3523 10.3492 9.82708L5.17417 5.89618C4.81652 5.64504 4.32202 5.6778 4.02417 5.97261C3.71367 6.26743 3.67917 6.73696 3.94252 7.06453L4.09317 7.20648L9.32567 11.0828C9.96967 11.5632 10.7505 11.8253 11.5682 11.8253C12.3835 11.8253 13.1782 11.5632 13.821 11.0828L19.0087 7.14097Z" fill="#4F4A6E"/>
                        </svg>{{ $user->email }}
                    </li>
                    <li><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.6633 16.7705C18.6633 16.7705 17.5047 17.9085 17.2207 18.2422C16.7582 18.7358 16.2132 18.9689 15.4988 18.9689C15.4301 18.9689 15.3568 18.9689 15.2881 18.9643C13.9279 18.8775 12.6639 18.3473 11.7159 17.8948C9.12383 16.6426 6.84772 14.8647 4.95631 12.6115C3.39463 10.7331 2.35046 8.99638 1.65892 7.13168C1.23301 5.99366 1.0773 5.10702 1.146 4.27064C1.19179 3.73591 1.39788 3.29259 1.778 2.91325L3.33967 1.35477C3.56408 1.14453 3.80222 1.03027 4.03579 1.03027C4.32431 1.03027 4.55787 1.20395 4.70442 1.3502C4.709 1.35477 4.71358 1.35934 4.71816 1.36391C4.99752 1.62442 5.26315 1.89407 5.54251 2.182C5.68448 2.32825 5.83103 2.4745 5.97758 2.62532L7.22784 3.87303C7.71328 4.35748 7.71328 4.80538 7.22784 5.28983C7.09503 5.42237 6.96679 5.55491 6.83398 5.68288C6.44929 6.07593 6.75149 5.77435 6.35305 6.13083C6.34389 6.13997 6.33473 6.14454 6.33015 6.15368C5.9363 6.54673 6.00958 6.93064 6.09201 7.19115C6.09659 7.20486 6.10117 7.21857 6.10575 7.23228C6.43091 8.01838 6.88888 8.75878 7.58499 9.64085L7.58957 9.64542C8.85357 11.1993 10.1863 12.4105 11.6563 13.3383C11.8441 13.4571 12.0365 13.5531 12.2196 13.6445C12.3845 13.7267 12.5402 13.8044 12.673 13.8867C12.6914 13.8958 12.7097 13.9095 12.728 13.9187C12.8837 13.9964 13.0303 14.033 13.1814 14.033C13.5615 14.033 13.7996 13.7953 13.8775 13.7176L14.7752 12.8217C14.9309 12.6664 15.1782 12.479 15.4667 12.479C15.7507 12.479 15.9842 12.6572 16.1262 12.8126C16.1308 12.8172 16.1308 12.8172 16.1354 12.8217L18.6588 15.34C19.1305 15.8062 18.6633 16.7705 18.6633 16.7705Z" fill="#4F4A6E" stroke="#4F4A6E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>{{ $user->contact_number }}
                    </li>
                </ul>
                </div>
    
            <div class="pl-sect">
<!-- Patient List Section -->
<section class="patient-list-section">
    <div class="search-container">
        <input type="text" id="patientSearch" placeholder="Search for name or patient ID...">
    </div>
    <div class="plist">
    <ul class="patient-list">
        @foreach($patients as $patient)
            <li class="patient-item" data-patient="{{ json_encode($patient) }}">
                <div class="prof-info">
                    @if($patient->profile_image)
                        <img src="{{ Storage::url($patient->profile_image) }}" alt="Profile Image">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Image">
                    @endif
                    <div class="patient-info">
                        <p class="patient-name">
                            <strong>
                            {{ $patient->last_name }}, {{ $patient->first_name }} 
                            @if($patient->middle_name)
                                {{ $patient->middle_name }}
                            @endif
                            </strong>
                        </p>
                        <p class="patient-id">P-{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="patient-actions">
                    <a href="{{ route('therapist.chat', $patient->id) }}" class="chat-icon">
                        <img src="{{ asset('images/nav/chat.png') }}" alt="Chat Icon">
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    <!-- Modal -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                    <svg width="40" height="40" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1222_33815)">
                        <path d="M9.90126 4.31726L9.90125 5.68105" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.90016 2.66187C9.83172 2.66187 9.77625 2.6059 9.77625 2.53687C9.77625 2.46783 9.83172 2.41187 9.90016 2.41187" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.90234 2.66187C9.97078 2.66187 10.0263 2.6059 10.0263 2.53687C10.0263 2.46783 9.97078 2.41187 9.90234 2.41187" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0.856689 12.8635H12.8641" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.92801 8.76801C4.99889 8.76801 5.86701 7.89989 5.86701 6.82901C5.86701 5.75813 4.99889 4.89001 3.92801 4.89001C2.85713 4.89001 1.98901 5.75813 1.98901 6.82901C1.98901 7.89989 2.85713 8.76801 3.92801 8.76801Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0.855469 12.8651V11.841C0.855469 10.1442 2.23098 8.76868 3.92777 8.76868C5.62455 8.76868 7.00006 10.1442 7.00006 11.841V12.8651" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.3935 6.71593C8.03441 7.34379 8.91209 7.7309 9.88017 7.7309C11.8428 7.7309 13.4339 6.13986 13.4339 4.17722C13.4339 2.21457 11.8428 0.623535 9.88017 0.623535C7.97695 0.623535 6.42316 2.11969 6.33081 4" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_1222_33815">
                        <rect width="14" height="14" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                        <h2>Patient Details</h2>
                    </div>
                    <div class="bot">
                        <p><strong>Name:</strong> <span id="modalPatientName"></span></p>
                        <p><strong>Patient ID:</strong> <span id="modalPatientId"></span></p>
                        <p><strong>Email:</strong> <span id="modalPatientEmail"></span></p>
                        <p><strong>Phone:</strong> <span id="modalPatientPhone"></span></p>
                        <p><strong>Address:</strong> <span id="modalPatientAddress">
                        <p><strong>Gender:</strong> <span id="modalPatientGender">
                    </div>
                </div>
                <div class="modal-buttons">
                    <button class="close">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>


            </div>
            </div>
        </main>

        <aside class="right-sidebar">
        <div class="upcoming">
            <h3>Upcoming</h3>
            <div class="upcoming-session" id="upcomingSession">
                <p><a href="{{ route('therapist.AppSched') }}" style="color: #4F4A6E; text-decoration: none; cursor: pointer;">Loading...</a></p>
            </div>
        </div>



            <div class="calendar card">
                <div id="calendar"></div>
                <script>
                    var appointmentsUrl = "{{ route('get.therapist_appointments') }}";
                </script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calendar Elements
    var calendarEl = document.getElementById('calendar');
    var eventModal = document.getElementById('eventModal');
    var eventModalSpan = eventModal.getElementsByClassName("close")[0];

    // Patient Elements
    const searchInput = document.getElementById('patientSearch');
    const patientList = document.querySelector('.patient-list');
    const patientItems = patientList.querySelectorAll('.patient-item');
    const patientModal = document.getElementById('patientModal');
    const patientModalCloseBtn = patientModal.querySelector('.close');
    const modalPatientName = document.getElementById('modalPatientName');
    const modalPatientId = document.getElementById('modalPatientId');
    const modalPatientEmail = document.getElementById('modalPatientEmail');
    const modalPatientPhone = document.getElementById('modalPatientPhone');
    const modalPatientAddress = document.getElementById('modalPatientAddress');
    const modalPatientGender = document.getElementById('modalPatientGender');


    // Calendar initialization
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
            eventModal.style.display = "block";
        }
    });

    calendar.render();

    // Event Modal Controls
    eventModalSpan.onclick = function() {
        eventModal.style.display = "none";
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        patientItems.forEach(item => {
            const name = item.querySelector('.patient-name').textContent.toLowerCase();
            const id = item.querySelector('.patient-id').textContent.toLowerCase();

            if (name.includes(searchTerm) || id.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

// Patient List Click Handler
patientList.addEventListener('click', function(e) {
    // Check if the clicked element is the chat icon or its parent link
    if (e.target.closest('.chat-icon') || e.target.closest('a[href*="chat"]')) {
        return; // Exit the function if chat icon is clicked
    }

    const patientItem = e.target.closest('.patient-item');
    if (patientItem) {
        const patientData = JSON.parse(patientItem.dataset.patient);
        
        // Format the full name
        const fullName = [
            patientData.last_name,
            patientData.first_name,
            patientData.middle_name || ''
        ].filter(Boolean).join(', ');
        
        // Update all modal content
        modalPatientName.textContent = fullName;
        modalPatientId.textContent = `P-${String(patientData.id).padStart(4, '0')}`;
        modalPatientEmail.textContent = patientData.email || 'Not provided';
        modalPatientPhone.textContent = patientData.contact_number || 'Not provided';
        modalPatientAddress.textContent = patientData.home_address || 'Not provided';
        modalPatientGender.textContent = patientData.gender || 'Not specified';
        
        patientModal.style.display = 'block';
        console.log('Modal should be visible now');
    }
});



    // Patient Modal Controls
    patientModalCloseBtn.addEventListener('click', function() {
        patientModal.style.display = 'none';
    });

    // Global Modal Close on Outside Click
    window.addEventListener('click', function(e) {
        if (e.target === eventModal) {
            eventModal.style.display = 'none';
        }
        if (e.target === patientModal) {
            patientModal.style.display = 'none';
        }
    });
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

                // Wrap the content in a link
                upcomingSessionDiv.innerHTML = `
                    <a href="{{ route('therapist.AppSched') }}" style="color: #4F4A6E; text-decoration: none; display: block; cursor: pointer;">
                        <p>${capitalizedMode} with ${nextEvent.extendedProps.patientName}</p>
                        <p>${appointmentDate} | ${startTime}</p>
                    </a>
                `;
            } else {
                upcomingSessionDiv.innerHTML = `
                    <a href="{{ route('therapist.AppSched') }}" style="color: #4F4A6E; text-decoration: none; display: block; cursor: pointer;">
                        <p>No upcoming sessions scheduled</p>
                    </a>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching appointments:', error);
            upcomingSessionDiv.innerHTML = `
                <a href="{{ route('therapist.AppSched') }}" style="color: #4F4A6E; text-decoration: none; display: block; cursor: pointer;">
                    <p>Error loading appointments</p>
                </a>
            `;
        });
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
    .fc-daygrid-event-harness {
        margin-top: 4px !important;
    }
</style>
<style>
    /* Existing styles */
    .custom-event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #8e7cc3;
        margin: 5px auto;
    }
    .fc-daygrid-event {
        background: none !important;
        border: none !important;
    }
    .fc-daygrid-event-harness {
        margin-top: 4px !important;
    }


</style>



            </div>
        </aside>
        </div>
        </div>
    
    </div>
    <!-- Add this somewhere in your blade template, preferably at the end of the body -->
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
                <div class="modal-footer">
                    <button class="close">Close</button>
                </div>
            </div>
        </div>
    </div>



</body>
</html>
</x-therapist-layout>   