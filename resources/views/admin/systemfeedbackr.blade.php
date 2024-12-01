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
            .table-container {
                max-height: 500px;
                overflow-y: auto;
            }

            @media print {
                body * {
                    visibility: hidden;
                }
                #printableTable, #printableTable * {
                    visibility: visible;
                }
                #printableTable {
                    position: absolute;
                    left: 0;
                    top: 0;
                }
                .export-btn, .print-btn, .report-dropdown {
                    display: none;
                }
                .table-container {
                    max-height: none !important;
                    overflow: visible !important;
                }
            }
        </style>
    </head>
    <body>
        <div class="reports-container">
            <div class="int">
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

                <div class="table-container">
                    <div id="printableTable">
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
                    </div>
                </div>
                
                <div class="button-container">
                    <button class="export-btn" onclick="printTable()">
                        <i class="fas fa-download"></i> Export Data
                    </button>
                </div>
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
                            const createdAt = new Date(feedback.created_at);
                            const formattedDate = (createdAt.getMonth() + 1).toString().padStart(2, '0') + 
                                                '/' + createdAt.getDate().toString().padStart(2, '0') + 
                                                '/' + createdAt.getFullYear();
                            
                            tableBody += `
                                <tr>
                                    <td>${feedback.user ? feedback.user.name : 'N/A'}</td>
                                    <td>${feedback.feedback}</td>
                                    <td>${formattedDate}</td>
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

            function printTable() {
                window.print();
            }
        </script>
    </body>
    </html>
</x-admin-layout>
