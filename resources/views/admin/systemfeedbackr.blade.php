<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/admin/systemfeedbackr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>System Feedback Reports</title>
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                #reports-section, #reports-section * {
                    visibility: visible;
                }
                #reports-section {
                    position: absolute;
                    left: 0;
                    top: 0;
                }
                .report-dropdown, .export-btn {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="reports-container" id="reports-section">
            <h1>System Feedback Reports</h1>
            <div class="report-dropdown">
                <label for="specificNameInput">Search: </label>
                <input type="text" id="specificNameInput" placeholder="Enter user name">
                <div class="date-filter">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="start_date" max="<?php echo date('Y-m-d'); ?>">
                    
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="end_date" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <button id="applyFilter" class="filt">Apply Filter</button>
                <button id="clearButton">Clear</button>
            </div>
            
            <table class="report-table" id="reportTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Feedback Description</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody id="feedbackTableBody">
                    @foreach($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->user->name ?? 'N/A' }}</td>
                            <td>{{ $feedback->feedback }}</td>
                            <td>{{ $feedback->created_at->format('m/d/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <button class="export-btn" onclick="window.print()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>
        </div>

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function updateTable() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const searchName = $('#specificNameInput').val();

                $.ajax({
                    url: '{{ route("admin.systemfeedbackr") }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        search_name: searchName
                    },
                    success: function(response) {
                        let tableBody = '';
                        response.data.forEach(function(feedback) {
                            tableBody += `
                                <tr>
                                    <td>${feedback.user ? feedback.user.name : 'N/A'}</td>
                                    <td>${feedback.feedback}</td>
                                    <td>${new Date(feedback.created_at).toLocaleDateString()}</td>
                                </tr>
                            `;
                        });
                        $('#feedbackTableBody').html(tableBody);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            let searchTimeout;
            $('#specificNameInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    updateTable();
                }, 300);
            });

            $('#applyFilter').click(updateTable);
            $('#clearButton').click(function() {
                $('#startDate, #endDate, #specificNameInput').val('');
                updateTable();
            });
        </script>
    </body>
    </html>
</x-admin-layout>
