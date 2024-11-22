<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Activity Logs</title>
        <link rel="stylesheet" href="{{ asset('css/admin/activitylogs.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            .table-container {
                max-height: 500px;
                overflow-y: auto;
                margin-bottom: 20px;
            }
            .report-table {
                width: 100%;
                border-collapse: collapse;
            }
            @media print {
                body * {
                    visibility: hidden;
                }
                .reports-container, .reports-container * {
                    visibility: visible;
                }
                .reports-container {
                    position: absolute;
                    left: 0;
                    top: 0;
                }
                .report-dropdown, .export-btn, .button-group {
                    display: none;
                }
                .table-container {
                    max-height: none;
                    overflow: visible;
                }
            }
        </style>
    </head>
    <body>
        <div class="reports-container">
            <div class="int">
            <h1>Activity Logs</h1>
            <div class="report-dropdown">
                <label for="specificNameInput">Search: </label>
                <input type="text" id="specificNameInput" placeholder="Search by name">
                <div class="user">
                    <label for="userFilterSelect">User: </label>
                    <select id="userFilterSelect">
                        <option value="all">All User Types</option>
                        <option value="user">Patient</option>
                        <option value="therapist">Therapist</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mode">
                    <label for="modeFilterSelect">Activity: </label>
                    <select id="modeFilterSelect">
                        <option value="all">All Activities</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>

                <div class="date-filter">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" max="<?php echo date('Y-m-d'); ?>">
                    
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" max="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <button id="applyFilter" class="filt">Apply Filter</button>
                <button id="clearButton">Clear</button>
            </div>

            <div class="table-container">
                <table class="report-table" id="activityTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Usertype</th>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody id="activityTableBody">
                        @foreach($activities as $activity)
                        <tr>
                            <td>{{ $activity['formatted_id'] }}</td>
                            <td>{{ $activity['name'] }}</td>
                            <td>{{ $activity['usertype'] === 'user' ? 'Patient' : ucfirst($activity['usertype']) }}</td>
                            <td>{{ $activity['activity'] }}</td>
                            <td>{{ $activity['date'] }}</td>
                            <td>{{ $activity['time'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="button-group" style="margin-top: 20px;">
                <button class="export-btn" onclick="window.print()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>
        </div>
    </div>
        <script>
            let searchTimeout;
            let currentFilters = {
                usertype: 'all',
                activity: 'all',
                start_date: '',
                end_date: ''
            };

            function updateTable(isSearch = false) {
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(() => {
                    const searchTerm = $('#specificNameInput').val();
                    
                    const filters = {
                        usertype: $('#userFilterSelect').val(),
                        activity: $('#modeFilterSelect').val(),
                        start_date: $('#startDate').val(),
                        end_date: $('#endDate').val(),
                        search: searchTerm,
                        is_search: isSearch
                    };

                    if (filters.start_date && filters.end_date && new Date(filters.start_date) > new Date(filters.end_date)) {
                        alert('Start date cannot be later than end date');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("admin.activitylogs") }}',
                        method: 'GET',
                        data: filters,
                        success: function(response) {
                            let tableBody = '';
                            if (response.data && Array.isArray(response.data)) {
                                response.data.forEach(function(activity) {
                                    const displayUsertype = activity.usertype === 'user' ? 'Patient' : 
                                        activity.usertype.charAt(0).toUpperCase() + activity.usertype.slice(1);
                                    
                                    tableBody += `
                                        <tr>
                                            <td>${activity.formatted_id}</td>
                                            <td>${activity.name}</td>
                                            <td>${displayUsertype}</td>
                                            <td>${activity.activity}</td>
                                            <td>${activity.date}</td>
                                            <td>${activity.time}</td>
                                        </tr>
                                    `;
                                });
                                $('#activityTableBody').html(tableBody);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.error('Response:', xhr.responseText);
                        }
                    });
                }, isSearch ? 300 : 0);
            }

            $(document).ready(function() {
                updateTable();

                $('#specificNameInput').on('input', function() {
                    updateTable(true);
                });

                $('#applyFilter').click(function() {
                    updateTable();
                });

                $('#clearButton').click(function() {
                    $('#userFilterSelect').val('all');
                    $('#modeFilterSelect').val('all');
                    $('#startDate, #endDate').val('');
                    $('#specificNameInput').val('');
                    updateTable();
                });  
            });
        </script>
    </body>
    </html>
</x-admin-layout>

