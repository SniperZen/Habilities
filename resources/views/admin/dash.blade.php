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
                    <div class="top">
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
                    <div id="chart-container">
                        <h1>System Usage Report</h1>
                        <div class="chart-header">
                            <select id="timeframeSelect">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="chart" id="system-usage-chart">
                            <canvas style="height: 762px; width: 1900px;"></canvas>
                        </div>
                    </div>
                </section>
        </div>
                                <aside class="sidebarr">
                            <div class="notifications card">
                    <div class="notifications-header">
                        <div style="display:flex; gap:5px;">
                        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.1225 18.6539L3.7475 18.0044H3.7475L4.1225 18.6539ZM3.35962 18.0803L2.61535 17.9877H2.61535L3.35962 18.0803ZM2.75572 14.8715L2.10163 15.2386L2.1062 15.2465L2.75572 14.8715ZM1.84402 13.247L2.49811 12.8799L2.49354 12.872L1.84402 13.247ZM16.1902 11.6867L16.5652 12.3362L16.1902 11.6867ZM16.0865 10.7497L16.3923 10.0648L16.3923 10.0648L16.0865 10.7497ZM13.6373 8.58901L12.983 8.95569L12.9878 8.96401L13.6373 8.58901ZM12.7718 7.04445L13.4261 6.67778L13.4213 6.66945L12.7718 7.04445ZM4.11189 4.70195L3.73689 4.05243L4.11189 4.70195ZM9.8328 18.5782L10.2078 19.2278L9.8328 18.5782ZM4.10388 18.1729C4.23011 17.1581 4.15269 15.7911 3.40523 14.4965L2.1062 15.2465C2.64424 16.1784 2.71439 17.1916 2.61535 17.9877L4.10388 18.1729ZM3.40976 14.5045L2.49806 12.88L1.18998 13.6141L2.10168 15.2386L3.40976 14.5045ZM16.3923 10.0648C15.6714 9.74301 14.8338 9.16143 14.2868 8.21401L12.9878 8.96401C13.7351 10.2584 14.8592 11.0231 15.7808 11.4345L16.3923 10.0648ZM14.2916 8.22237L13.4261 6.67781L12.1175 7.41109L12.9831 8.95565L14.2916 8.22237ZM13.4213 6.66945C11.46 3.27237 7.12537 2.09609 3.73689 4.05243L4.48689 5.35147C7.15211 3.8127 10.5718 4.73398 12.1223 7.41945L13.4213 6.66945ZM16.5652 12.3362C17.0741 12.0423 17.1889 11.4976 17.1414 11.1131C17.0941 10.7297 16.8659 10.2763 16.3923 10.0648L15.7808 11.4345C15.7201 11.4075 15.6868 11.3691 15.6722 11.3469C15.6586 11.3261 15.6543 11.3098 15.6527 11.2969C15.6512 11.2847 15.6493 11.2515 15.6686 11.2031C15.6903 11.1487 15.7374 11.082 15.8152 11.0372L16.5652 12.3362ZM2.49354 12.872C0.968569 10.2307 1.86562 6.86486 4.48689 5.35147L3.73689 4.05243C0.392359 5.9834 -0.741314 10.2691 1.1945 13.622L2.49354 12.872ZM3.7475 18.0044C3.82707 17.9585 3.90998 17.9517 3.96784 17.9607C4.01905 17.9686 4.04552 17.9869 4.0538 17.9932C4.06271 18.0001 4.07376 18.011 4.08439 18.0327C4.09576 18.056 4.11231 18.1051 4.10388 18.1729L2.61535 17.9877C2.55028 18.5109 2.83032 18.9438 3.13728 19.1807C3.44471 19.418 3.98009 19.6022 4.4975 19.3035L3.7475 18.0044ZM15.8152 11.0372L3.7475 18.0044L4.4975 19.3035L16.5652 12.3362L15.8152 11.0372ZM10.3654 15.0496C10.9062 15.9863 10.5624 17.291 9.4578 17.9287L10.2078 19.2278C11.9496 18.2221 12.6643 16.0314 11.6644 14.2996L10.3654 15.0496ZM9.4578 17.9287C8.35323 18.5664 7.05143 18.2118 6.51064 17.2752L5.2116 18.0252C6.21146 19.757 8.46603 20.2334 10.2078 19.2278L9.4578 17.9287ZM4.18845 2.36718C4.52913 2.95724 4.32696 3.71175 3.73689 4.05243L4.48689 5.35147C5.7944 4.59658 6.24238 2.92468 5.48749 1.61718L4.18845 2.36718ZM3.73689 4.05243C3.14683 4.3931 2.39232 4.19093 2.05164 3.60087L0.752603 4.35087C1.50749 5.65837 3.17939 6.10635 4.48689 5.35147L3.73689 4.05243ZM2.05164 3.60087C1.71097 3.0108 1.91314 2.25629 2.5032 1.91561L1.7532 0.616576C0.445699 1.37146 -0.00228405 3.04336 0.752603 4.35087L2.05164 3.60087ZM2.5032 1.91561C3.09327 1.57494 3.84778 1.77711 4.18845 2.36718L5.48749 1.61718C4.73261 0.309673 3.06071 -0.138312 1.7532 0.616576L2.5032 1.91561Z" fill="#303B1D"/>
                        </svg>
                        <h3>Notifications</h3>
                        </div>
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
                                <a href="{{ route('admin.systemfeedbackr', ['feedback_id' => $feedback_id]) }}" class="notification-link">
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
                <section class="therapy-center-reports">
        <div class="chart-card card">
            <h1>Gender Count</h1>
                <div class="chart-container">
                    <canvas id="gender-chart"></canvas>
                </div>
        </div>
    </section>
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



                            <!-- <div class="calendar">
                                <div id="calendar"></div>
                            </div> -->
                </aside>
            </div>
            <div class="bottom">
            <div class="chart-card card" style="width: 50%;">
            <h1>Onsite Appointments Report</h1>
                <div>
                    <select id="filterSelect" class="form-select">
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="yearly">Yearly</option>
                    </select>
                </div>
            <div class="chart-container">
                <canvas id="appointmentChart"></canvas>
            </div>
        </div>

        <div class="chart-card card" style="width: 50%;">
            <h1>Tele-therapy Appointments Report</h1>
                <div>
                    <select id="teletherapyFilterSelect" class="form-select">
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="teletherapyChart"></canvas>
                </div>
        </div>
                            </div>
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

//system usage report
    document.addEventListener('DOMContentLoaded', function() {
    let chart;
    const ctx = document.querySelector('#system-usage-chart canvas').getContext('2d');
    const timeframeSelect = document.getElementById('timeframeSelect');

    function formatWeekLabel(isoWeek) {
        // Extract the week number from the ISO format (last two digits)
        const weekNumber = parseInt(isoWeek.toString().slice(-2));
        return `Week ${weekNumber}`;
    }

    function createChart(data, label) {
        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => {
                    if (label === 'Weekly') {
                        return formatWeekLabel(item.week);
                    }
                    return item.date || item.month;
                }),
                datasets: [{
                    label: label,
                    data: data.map(item => item.count),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Users'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: label
                        }
                    }
                }
            }
        });
    }

    function fetchData() {
        fetch('/admin/system-usage')
            .then(response => response.json())
            .then(data => {
                timeframeSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    switch(selectedValue) {
                        case 'daily':
                            createChart(data.daily, 'Daily');
                            break;
                        case 'weekly':
                            createChart(data.weekly, 'Weekly');
                            break;
                        case 'monthly':
                            createChart(data.monthly, 'Monthly');
                            break;
                    }
                });

                // Initial load
                createChart(data.daily, 'Daily');
            })
            .catch(error => console.error('Error:', error));
    }

    fetchData();
});
//system usage report/on-site chart
document.addEventListener('DOMContentLoaded', function() {
        let chart;
        const ctx = document.getElementById('appointmentChart').getContext('2d');
        const filterSelect = document.getElementById('filterSelect');

        function createChart() {
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Onsite Appointments',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 40,
                            ticks: {
                                stepSize: 5
                            },
                            title: {
                                display: true,
                                text: 'Number of Appointments'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time Period'
                            }
                        }
                    }
                }
            });
        }

        function fetchData() {
            const filter = filterSelect.value;
            fetch(`/admin/onsite-appointments-data?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    if (data.labels.length === 0) {
                        console.warn('No data received from the server');
                    }
                    updateChart(data);
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                });
        }

        function updateChart(data) {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.values;
            chart.update();
        }

        createChart();
        fetchData();

        filterSelect.addEventListener('change', fetchData);
    });

//onsite chart tele-therapist chart
document.addEventListener('DOMContentLoaded', function() {
        let chart;
        const ctx = document.getElementById('teletherapyChart').getContext('2d');
        const teletherapyFilterSelect = document.getElementById('teletherapyFilterSelect');
        

        function createChart() {
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Teletherapy Appointments',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 40,
                            ticks: {
                                stepSize: 5
                            },
                            title: {
                                display: true,
                                text: 'Number of Appointments'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time Period'
                            }
                        }
                    }
                }
            });
        }

        function fetchData() {
            const filter = teletherapyFilterSelect.value;
            fetch(`/admin/teletherapy-appointments-data?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    if (data.labels.length === 0) {
                        console.warn('No data received from the server');
                    }
                    updateChart(data);
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                });
        }

        function updateChart(data) {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.values;
            chart.update();
        }

        createChart();
        fetchData();

        teletherapyFilterSelect.addEventListener('change', fetchData);
    });
//end tele-therapy chart gender chart
// Function to fetch gender data
async function fetchGenderData() {
    try {
        const response = await fetch('/admin/dashboard-counts');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data.gender_distribution;
    } catch (error) {
        console.error('Error fetching gender data:', error);
        return null;
    }
}

// Modified createGenderChart function with loading state
function createGenderChart(genderData) {
    const canvas = document.getElementById('gender-chart');
    if (!canvas) {
        console.error('Cannot find gender chart canvas element');
        return;
    }

    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart if it exists
    if (window.genderChart instanceof Chart) {
        window.genderChart.destroy();
    }

    // Calculate percentages
    const total = Object.values(genderData).reduce((a, b) => a + b, 0);
    const percentages = {};
    Object.entries(genderData).forEach(([key, value]) => {
        percentages[key] = ((value / total) * 100).toFixed(1);
    });

    window.genderChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(genderData),
            datasets: [{
                data: Object.values(percentages),
                backgroundColor: [
                    '#68a85c',  // Darker green (Male)
                    '#a8d6a0',  // Medium green (Female)
                    '#c5e5bc'   // Lighter green (Others)
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Gender Distribution 2024',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            // Add both percentage and actual count
                            const count = genderData[context.label];
                            label += context.formattedValue + '% (' + count + ')';
                            return label;
                        }
                    }
                }
            },
            cutout: '70%',
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

// Function to show loading state
function showChartLoading() {
    const canvas = document.getElementById('gender-chart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#666';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Loading...', canvas.width/2, canvas.height/2);
    }
}

// Function to show error state
function showChartError() {
    const canvas = document.getElementById('gender-chart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#dc3545';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Error loading data', canvas.width/2, canvas.height/2);
    }
}

// Function to initialize and update the chart
async function updateGenderChart() {
    showChartLoading();
    const genderData = await fetchGenderData();
    if (genderData) {
        createGenderChart(genderData);
    } else {
        showChartError();
    }
}

// Initialize the chart when the DOM is loaded
document.addEventListener('DOMContentLoaded', updateGenderChart);

// Update the chart every 5 minutes (300000 milliseconds)
setInterval(updateGenderChart, 300000);

//end gender chart

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