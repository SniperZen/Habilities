<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Reports</title>
    <link rel="stylesheet" href="{{ asset('css/admin/report.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<style>

</style>
<body>
    <div class="dashboard-container">
        <!-- Header Section -->
        <header class="dashboard-header">
            <h1>Reports</h1>
        </header>

        <!--<div class="search-filter">
            <img src="{{ asset('images/icons/search.png') }}" alt="">
                <input type="text" placeholder="Search Reports">
                <div class="filter-options">
                    <span>Filter by:</span>
                    <button>Daily</button>
                    <button>Weekly</button>
                    <button>Monthly</button>
                    <button>Yearly</button>
                </div>
            </div>-->
        <section class="system-reports">
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
            
            <div class="gent-act">
                <div class="active-users card">
                    <h3>Active Users</h3>
                    <p class="user-count">0</p>
                    <span class="user-growth">0%</span>
                </div>

                <div class="gender-count card">
                    <h3>Gender Count</h3>
                    <p>2024</p>
                    <canvas class="gender-icon" id="gender-chart"></canvas>            
                </div>
        </section>

        <!-- Account Reports Section -->
        <section class="account-reports">
            <div class="report-card card">
                <h3>Therapist Accounts</h3>
                <p class="account-count">14</p>
            </div>
            <div class="report-card card">
                <h3>Patient Accounts</h3>
                <p class="account-count">21</p>
            </div>
            <div class="report-card card">
                <h3>Supervised Patient Accounts</h3>
                <p class="account-count">24</p>
            </div>
        </section>

<section class="activity-logs card">
    <h2>Activity Logs</h2>
    <div class="filters">
        <div class="user-filter">
            <select id="userFilterSelect">
                <option value="all">All User Types</option>
                <option value="user">Patient</option>
                <option value="therapist">Therapist</option>
                <option value="admin">Admin</option>
                <option value="specificName">Search User Name</option>
                <option value="specificID">Search User ID</option>
            </select>
            <input type="text" id="specificNameInput" placeholder="Enter user name" style="display: none;">
            <input type="text" id="specificIDInput" placeholder="Enter user ID" style="display: none;">
        </div>
        
        <div class="log-type-filter">
            <select id="logTypeSelect">
                <option value="all">All Activities</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
            </select>
        </div>
        
        <div class="date-filter">
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" value="{{ request('startDate') }}">
            
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" value="{{ request('endDate') }}">
        </div>
        
        <button id="filterButton">Filter</button>
    </div>
    <div class="table-wrapper">
    <table id="activityLogsTable">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Timestamp</th>
                <th>User</th>
                <th>Activity</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activityLogs as $log)
                <tr class="log-entry" data-activity="{{ $log['action'] }}" data-user-type="{{ $log['userType'] }}">
                    <td>{{ $log['userId'] }}</td>
                    <td>{{ $log['timestamp'] }}</td>
                    <td>{{ $log['name'] }}</td>
                    <td>{{ ucfirst($log['action']) }}</td>
                    <td>{{ ucfirst($log['userType']) }}</td>
                </tr>
            @endforeach
            @if ($activityLogs->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center;">No activity logs available.</td>
                </tr>
            @endif
        </tbody>
    </table>
    </div>
</section>

<script>
    document.getElementById('userFilterSelect').addEventListener('change', function() {
        const specificNameInput = document.getElementById('specificNameInput');
        const specificIDInput = document.getElementById('specificIDInput');
        specificNameInput.style.display = this.value === 'specificName' ? 'block' : 'none';
        specificIDInput.style.display = this.value === 'specificID' ? 'block' : 'none';
    });

    document.getElementById('filterButton').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const userType = document.getElementById('userFilterSelect').value;
        const specificName = document.getElementById('specificNameInput').value;
        const specificID = document.getElementById('specificIDInput').value;

        const url = new URL(window.location.href);
        url.searchParams.set('startDate', startDate);
        url.searchParams.set('endDate', endDate);
        url.searchParams.set('userType', userType);
        if (userType === 'specificName') {
            url.searchParams.set('specificUser', specificName);
        } else if (userType === 'specificID') {
            url.searchParams.set('specificUserID', specificID);
        }

        localStorage.setItem('filterClicked', 'true');
        window.location.href = url.toString();
    });

    window.onload = function() {
        const logEntries = document.querySelectorAll('.log-entry');

        // Add event listener for log type filter
        document.getElementById('logTypeSelect').addEventListener('change', function() {
            const selectedType = this.value;
            logEntries.forEach(entry => {
                if (selectedType === 'all' || entry.dataset.activity === selectedType) {
                    entry.style.display = '';
                } else {
                    entry.style.display = 'none';
                }
            });
        });

        // Add event listener for user type filter
        document.getElementById('userFilterSelect').addEventListener('change', function() {
            const selectedUserType = this.value;
            logEntries.forEach(entry => {
                if (selectedUserType === 'all' || entry.dataset.userType === selectedUserType) {
                    entry.style.display = '';
                } else {
                    entry.style.display = 'none';
                }
            });
        });

        // Search for specific user name
        document.getElementById('specificNameInput').addEventListener('input', function() {
            const specificUser = this.value.toLowerCase();
            logEntries.forEach(entry => {
                const userName = entry.children[2].textContent.toLowerCase();
                entry.style.display = userName.includes(specificUser) ? '' : 'none';
            });
        });

        // Search for specific user ID
        document.getElementById('specificIDInput').addEventListener('input', function() {
            const specificUserID = this.value.toLowerCase();
            logEntries.forEach(entry => {
                const userID = entry.children[0].textContent.toLowerCase(); // Assuming User ID is in the first column
                entry.style.display = userID.includes(specificUserID) ? '' : 'none';
            });
        });
    };
</script>

    <!-- Therapy Center Reports Section -->
    <section class="therapy-center-reports">
        <div class="chart-card card">
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

        <div class="chart-card card">
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
    </section>

        <!--<section class="user-feedback-reports">
            <div class="user-level card">
                <h3>User Level</h3>
                <div class="donut-chart" id="user-level-chart"></div> 
            </div>
            <div class="user-experience card">
                <h3>User Experience Rating</h3>
                <div class="rating-bars">

                    <div class="rating-bar amazing">
                        <span class="rating-label">Amazing</span>
                        <div class="bar">
                            <div class="fill"></div>
                        </div>
                    </div>
                    <div class="rating-bar good">
                        <span class="rating-label">Good</span>
                        <div class="bar">
                            <div class="fill"></div>
                        </div>
                    </div>
                    <div class="rating-bar neutral">
                        <span class="rating-label">Neutral</span>
                        <div class="bar">
                            <div class="fill"></div>
                        </div>
                    </div>
                    <div class="rating-bar bad">
                        <span class="rating-label">Bad</span>
                        <div class="bar">
                            <div class="fill"></div>
                        </div>
                    </div>
                    <div class="rating-bar terrible">
                        <span class="rating-label">Terrible</span>
                        <div class="bar">
                            <div class="fill"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>-->

        <section class="feedback-table card">
        <h1>System Feedback</h1>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Submitted On</th>
                        <th>User Experience Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $feedback)
                        <tr data-feedback-id="{{ $feedback->id }}" class="{{ $feedback->id == $highlightFeedbackId ? 'highlight' : '' }}">
                            <td>{{ $feedback->user->name ?? 'N/A' }}</td>
                            <td>{{ $feedback->created_at->format('Y-m-d') }}</td>
                            <td>{{ $feedback->feedback }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </section>


    </div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchDashboardCounts();
});

function fetchDashboardCounts() {
    fetch('/admin/dashboard-counts')
        .then(response => response.json())
        .then(data => {
            // Update active users in the gent-act section
            const userCount = document.querySelector('.active-users .user-count');
            const userGrowth = document.querySelector('.active-users .user-growth');
            userCount.textContent = data.active_users;
            userGrowth.textContent = `${data.user_growth > 0 ? '+' : ''}${data.user_growth}%`;
            
            // Update account reports section
            const accountCounts = document.querySelectorAll('.account-reports .account-count');
            accountCounts[0].textContent = data.therapist_accounts;
            accountCounts[1].textContent = data.patient_accounts;
            accountCounts[2].textContent = data.supervised_accounts;

            // Create gender distribution chart
            if (data.gender_distribution) {
                console.log('Gender data:', data.gender_distribution); // Debug log
                createGenderChart(data.gender_distribution);
            }
        })
        .catch(error => console.error('Error fetching dashboard counts:', error));
}

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

    window.genderChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(genderData),
            datasets: [{
                data: Object.values(genderData),
                backgroundColor: [
                    '#FF6384',  // Female
                    '#36A2EB',  // Male
                    '#FFCE56'   // Other
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20
                    }
                }
            }
        }
    });
}
</script>

<script>
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
</script>
<script>
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

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const highlightFeedbackId = '{{ $highlightFeedbackId }}';
    
    if (highlightFeedbackId) {
        const feedbackRow = document.querySelector(`tr[data-feedback-id="${highlightFeedbackId}"]`);
        if (feedbackRow) {
            feedbackRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            feedbackRow.classList.add('highlight');
            setTimeout(() => {
                feedbackRow.classList.remove('highlight');
            }, 3000);
        }
    }
});
</script>


</html>

</x-admin-layout>
