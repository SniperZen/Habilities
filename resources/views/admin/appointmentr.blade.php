<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="{{ asset('css/admin/appointmentr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        <div class="reports-container">
            <h1>Appointment Reports</h1>
            <!-- <div class="search-filter-container">
                <div class="search-bar">
                    <input type="text" placeholder="Search Reports">
                    <button class="search-icon"><i class="fas fa-search"></i></button>
                </div>
                <div class="filter-buttons">
                    <span>Filter by:</span>
                    <button class="filter-btn active">Daily</button>
                    <button class="filter-btn">Weekly</button>
                    <button class="filter-btn">Monthly</button>
                    <button class="filter-btn">Yearly</button>
                </div>
            </div> -->

            <div class="report-dropdown">
                <!--<div class= "user">
                    <label for="userFilterSelect">User: </label>
                    <select id="userFilterSelect">
                        <option value="all">All User Types</option>
                        <option value="user">Patient</option>
                        <option value="therapist">Therapist</option>
                        <option value="specificName">Search User Name</option>
                    </select>
                    <input type="text" id="userNameInput" placeholder="Enter User Name" style="display: none;">

                    <script>
                        document.getElementById('userFilterSelect').addEventListener('change', function() {
                            const userNameInput = document.getElementById('userNameInput');
                            if (this.value === 'specificName') {
                                userNameInput.style.display = 'block';
                            } else {
                                userNameInput.style.display = 'none';
                            }
                        });
                    </script>
                </div>-->

                <div class="mode">
                    <label for="modeFilterSelect">Mode: </label>
                    <select id="modeFilterSelect">
                        <option value="all">All</option>
                        <option value="user">Face to face</option>
                        <option value="therapist">Tele-Therapy</option>
                    </select>
                </div>

                <div class="status">
                    <label for="statusFilterSelect">Status: </label>
                    <select id="statusFilterSelect">
                        <option value="all">All</option>
                        <option value="user">Completed</option>
                        <option value="therapist">Declined</option>
                        <option value="therapist">Pending</option>

                    </select>
                </div>

                <div class="date-filter">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate">
                    
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate">
                </div>
                
                <button id="clearButton">Clear</button>
            </div>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Therapist</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Mode</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Maria Lourdes Solita G. Cruz</td>
                        <td>Maria Lourdes Solita G. Cruz</td>
                        <td>04/02/2024</td>
                        <td>10:00 AM - 11:00 AM</td>
                        <td>Face-to-Face</td>
                        <td>Pending</td>

                    </tr>
                    <!-- Repeat rows as needed -->
                </tbody>
            </table>
            <button class="export-btn">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
    </body>
    </html>
</x-admin-layout>