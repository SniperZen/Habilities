<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dash.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">



</head>
<style>
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            margin: 0;
            text-align: center;
            font-weight: bold;
            color: black;
        }

        .fc .fc-button-primary:disabled{
            background-color: #C1DB9B;
        }
        .fc .fc-button-primary{
            background-color: #74A36B;
        }

        .fc .fc-button-primary:hover{
            background-color: #8fa770;
        }
        .fc .fc-daygrid-day-number, .fc-col-header-cell-cushion {
            color: black !important;
        }
        .fc-today-button {
            display: none;
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
            background-color: #74A36B;
        }

        .fc-direction-ltr {
            height: 469px;
        }
        .fc .fc-button-group {
            position: relative;
            display: inline-flex;
            gap: 9px;
        }
        .chart-section {
            padding: 1rem;
            margin: 1rem 0;
        }

        .chart {
            height: 300px;  /* Adjust height as needed */
            width: 100%;
            position: relative;
        }
    </style>
<body>
    <div class="dashboard-container">
        
            <div class="main-content">
                <!-- Header Section -->
                <header class="admin-header card">
                    <div class="admin-welcome">
                        <div><img src="{{ asset('images/icons/design.png') }}" alt="Admin Avatar" class="admin-avatar"></div>
                        <div><h1>Hello, Admin!</h1>
                        <p>Let’s dive in with a positive and productive mindset. Review the latest reports to keep Habilities thriving!</p><br>
                    </div></div>
                </header>

                <div class="incont">
                    <div class="left">
                <!-- Navigation Section -->
                <nav class="dashboard-nav">
                    <a href="{{ route('admin.usersPatient') }}" class="nav-button" style="text-decoration: none;">
                        <svg width="60" height="60" viewBox="0 0 47 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="23.5462" cy="23.5148" rx="23.096" ry="23.1618" fill="#74A36B"/>
                            <path d="M33.3998 24.8179C35.1424 26.1198 36.6998 29.3979 36.6998 31.4178C36.6998 32.047 36.2407 32.5571 35.6744 32.5571H35.0498M28.4498 19.4812C29.5771 18.8291 30.3355 17.6102 30.3355 16.2143C30.3355 14.8183 29.5771 13.5995 28.4498 12.9474M11.3252 32.5571H29.3887C29.955 32.5571 30.4141 32.047 30.4141 31.4178C30.4141 27.4624 27.1096 24.256 20.3569 24.256C13.6043 24.256 10.2998 27.4624 10.2998 31.4178C10.2998 32.047 10.7589 32.5571 11.3252 32.5571ZM24.1284 16.2143C24.1284 18.2972 22.4398 19.9857 20.3569 19.9857C18.274 19.9857 16.5855 18.2972 16.5855 16.2143C16.5855 14.1314 18.274 12.4429 20.3569 12.4429C22.4398 12.4429 24.1284 14.1314 24.1284 16.2143Z" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <p>Patient</p>
                    </a>

                    <!-- Therapist Button -->
                    <a href="{{ route('admin.usersTherapist') }}" class="nav-button" style="text-decoration: none;">
                        <svg width="60" height="60" viewBox="0 0 47 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="23.5462" cy="23.5148" rx="23.096" ry="23.1618" fill="#74A36B"/>
                            <path d="M33.3998 24.8179C35.1424 26.1198 36.6998 29.3979 36.6998 31.4178C36.6998 32.047 36.2407 32.5571 35.6744 32.5571H35.0498M28.4498 19.4812C29.5771 18.8291 30.3355 17.6102 30.3355 16.2143C30.3355 14.8183 29.5771 13.5995 28.4498 12.9474M11.3252 32.5571H29.3887C29.955 32.5571 30.4141 32.047 30.4141 31.4178C30.4141 27.4624 27.1096 24.256 20.3569 24.256C13.6043 24.256 10.2998 27.4624 10.2998 31.4178C10.2998 32.047 10.7589 32.5571 11.3252 32.5571ZM24.1284 16.2143C24.1284 18.2972 22.4398 19.9857 20.3569 19.9857C18.274 19.9857 16.5855 18.2972 16.5855 16.2143C16.5855 14.1314 18.274 12.4429 20.3569 12.4429C22.4398 12.4429 24.1284 14.1314 24.1284 16.2143Z" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <p>Therapist</p>
                    </a>

                    <!-- Therapy Center Button -->
                    <a href="{{ route('admin.therapycenter') }}" class="nav-button" style="text-decoration: none;">
                        <svg width="60" height="60" viewBox="0 0 46 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="23.2387" cy="23.5147" rx="22.351" ry="23.1618" fill="#74A36B"/>
                            <path d="M22.0728 33.2941H12.5659C12.0136 33.2941 11.5659 32.8464 11.5659 32.2941V16.7059C11.5659 14.4967 13.3568 12.7059 15.5659 12.7059H29.9169C32.126 12.7059 33.9169 14.4967 33.9169 16.7059V22.2082" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5327 25.5735H20.2579" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5327 20.4265H23.983" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M31.6755 27.5037C31.6755 29.4726 30.1441 31.0074 28.3294 31.0074C26.5148 31.0074 24.9834 29.4726 24.9834 27.5037C24.9834 25.5348 26.5148 24 28.3294 24C30.1441 24 31.6755 25.5348 31.6755 27.5037Z" stroke="white" stroke-width="2"/>
                            <path d="M30.8125 30.0772L34.5377 33.9375" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <p>Therapy <br> Center</p>
                    </a>

                    <!-- Reports Button -->
                    <a href="{{ route('admin.report') }}" class="nav-button" style="text-decoration: none;">
                        <svg width="60" height="60" viewBox="0 0 46 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="23.2387" cy="23.5148" rx="22.351" ry="23.1618" fill="#74A36B"/>
                            <path d="M20.2001 23.3829L22.6751 25.8579L27.6251 20.9079M13.6001 15.9579L21.2864 12.1147C22.68 11.4179 24.3202 11.4179 25.7138 12.1147L33.4001 15.9579C33.4001 15.9579 33.4001 23.1849 33.4001 26.9964C33.4001 30.8079 29.8768 33.3808 23.5001 37.4079C17.1234 33.3808 13.6001 29.9829 13.6001 26.9964V15.9579Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p>Reports</p>
                    </a>

                </nav>

                <!-- Statistics Section -->
                <section class="statistics-section">
                    <div class="stat-card card">
                        <h3>New Users</h3>
                        <p class="stat-number" id="newUsersCount">--</p>
                        <p class="stat-change">
                            <span id="newUsersGrowth">--</span>% 
                            <span id="newUsersGrowthArrow">&#8599;</span>
                        </p>
                    </div>


                    <div class="stat-card card">
                        <h3>Active Users</h3>
                        <p class="stat-number" id="activeUsersCount">--</p>
                        <p class="stat-change">
                            <span id="userGrowth">--</span>% 
                            <span id="growthArrow">&#8599;</span>
                        </p>
                    </div>


                    <div class="stat-card card">
                        <h3>Visits</h3>
                        <p class="stat-number" id="visitsCount">--</p>
                        <p class="stat-change">
                            <span id="visitsGrowth">--</span>% 
                            <span id="visitsGrowthArrow">&#8599;</span>
                        </p>
                    </div>
                </section>

                <!-- Chart Section -->
                <section class="chart-section card">
                                    <h3>Weekly Usage Report</h3>
                                    <canvas id="weekly-usage-chart" style="height: 400px; width: 100%;"></canvas>
                                </section>
                                </div>
                                <aside class="sidebarr">
                            <div class="notifications card">
                    <div class="notifications-header">
                        <h3>Notifications</h3>
                        <a href="#" id="toggleNotifications" class="see-more">See More ›</a>
                    </div>
                    <ul id="notificationsList" class="notifications-list">
                        @foreach(auth()->user()->notifications()->latest()->take(8)->get() as $index => $notification)
                            @php
                                $profile_image = asset('images/others/default-prof.png'); // Default image
                                $sender_name = 'Unknown';
                                
                                if ($notification->type === 'App\Notifications\NewPatientFeedbackNotification') {
                                    $user = \App\Models\User::find($notification->data['user_id']);
                                    if ($user) {
                                        $sender_name = $user->name;
                                        $profile_image = $user->profile_image 
                                            ? asset('storage/' . $user->profile_image) 
                                            : $profile_image;
                                    }
                                }
                                $feedback_id = $notification->data['feedback_id'] ?? '';
                            @endphp
                            <li class="notification-item {{ $notification->read_at ? '' : 'unread' }} {{ $index >= 4 ? 'hidden' : '' }}" data-id="{{ $notification->id }}">
                                <a href="{{ route('admin.report', ['feedback_id' => $feedback_id]) }}" class="notification-link">
                                    <img src="{{ $profile_image }}" alt="{{ $sender_name }}'s Avatar" class="notification-avatar">
                                    <div class="notification-content">
                                        <p class="notification-title">{{ $sender_name }}</p>
                                        <p class="notification-description">{{ $notification->data['message'] ?? 'No message' }}</p>
                                    </div>
                                    @if($notification->read_at === null)
                                        <span class="unread-indicator"></span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                        @if(auth()->user()->notifications()->count() == 0)
                            <li class="no-notifications">No notifications</li>
                        @endif
                    </ul>
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
                                item.classList.toggle('hidden');
                            }
                        });

                        toggleButton.textContent = isExpanded ? 'See Less ‹' : 'See More ›';
                    });

                    // Mark notification as read when clicked
                    notificationsList.addEventListener('click', function(e) {
                        const notificationItem = e.target.closest('.notification-item');
                        if (notificationItem) {
                            const notificationId = notificationItem.dataset.id;
                            fetch(`/mark-notification-as-read/${notificationId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                            });
                        }
                    });
                });
                </script>

                <style>
                .hidden {
                    display: none;
                }
                .unread-indicator {
                        width: 10px;
                        height: 10px;
                        background-color: #a3eb8a;
                        border-radius: 50%;
                        display: inline-block;
                        margin-left: 5px;
                    }
                .notification-item.unread {
                    background-color: #f0f8ff;
                }

                .notification-item.unread:hover{
                    background-color: #caddc6;
                }
                .notification-link {
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    color: inherit;
                }
                .notifications-list {
                    max-height: 324px;
                    overflow-y: auto;
                    overflow-x: hidden;
                    transition: max-height 0.3s ease;
                }

                </style>



                            <div class="calendar">
                                <div id="calendar"></div>
                            </div>
                            </aside>
                </div>
            </div>
        </div>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',  
            events: '/get-appointments',  
            headerToolbar: {
                left: 'today',
                center: 'title',
                right: 'prev,next'
             },
            eventTimeFormat: {            
                hour: '2-digit',
                minute: '2-digit',
                meridiem: true
            },
            editable: false,              
            selectable: true,
            dayMaxEvents: true         
        });

        calendar.render();
    });
</script>
<script>
    async function fetchDashboardCounts() {
        try {
            const response = await fetch('/admin/dashboard-counts');
            const data = await response.json();

            // Update New Users card
            updateCard('newUsersCount', data.new_users);
            updateGrowthIndicator('newUsersGrowth', data.new_user_growth, 'newUsersGrowthArrow');

            // Update Active Users card
            updateCard('activeUsersCount', data.active_users);
            updateGrowthIndicator('userGrowth', data.user_growth, 'growthArrow');
        } catch (error) {
            console.error('Error fetching dashboard counts:', error);
        }
    }

    async function fetchSystemUsage() {
        try {
            const response = await fetch('/admin/system-usage');
            const data = await response.json();

            // Update Visits card
            const latestDaily = data.daily[data.daily.length - 1];
            document.getElementById('visitsCount').innerText = latestDaily ? latestDaily.count : 0;
            document.getElementById('visitsGrowth').innerText = data.daily_growth.toFixed(2);
            updateGrowthIndicator('visitsGrowth', data.daily_growth, 'visitsGrowthArrow');
        } catch (error) {
            console.error('Error fetching system usage:', error);
        }
    }

    async function fetchWeeklyUsage() {
    try {
        const response = await fetch('/admin/system-usage');
        const data = await response.json();

        const weeklyData = data.weekly;
        const labels = weeklyData.map(entry => `Week ${entry.week % 100}`); // Adjust based on your week format
        const counts = weeklyData.map(entry => entry.count);

        const ctx = document.getElementById('weekly-usage-chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Weekly User Logins',
                    data: counts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Users'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Weeks'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error fetching weekly usage:', error);
    }
}


    function updateCard(elementId, value) {
        document.getElementById(elementId).innerText = value;
    }

    function updateGrowthIndicator(growthElementId, growthValue, arrowElementId) {
        document.getElementById(growthElementId).innerText = growthValue;
        const arrow = growthValue > 0 ? '&#8599;' : (growthValue < 0 ? '&#8600;' : '');
        document.getElementById(arrowElementId).innerHTML = arrow;
    }

    // Call functions when the page loads
    window.onload = function() {
        fetchDashboardCounts();
        fetchSystemUsage();
        fetchWeeklyUsage();
    };
</script>



</body>
</html>
</x-admin-layout>