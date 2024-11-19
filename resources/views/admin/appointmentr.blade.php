<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Appointment Reports</title>
        <link rel="stylesheet" href="{{ asset('css/admin/appointmentr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            }
        </style>
    </head>
    <body>
        <div class="reports-container">
            <h1>Appointment Reports</h1>

            <div class="report-dropdown">
                <form action="{{ route('admin.appointmentr') }}" method="GET" id="filterForm">
                    <div class="mode">
                        <label for="modeFilterSelect">Mode: </label>
                        <select id="modeFilterSelect" name="mode">
                            <option value="all">All</option>
                            <option value="On-site" {{ request('mode') == 'On-site' ? 'selected' : '' }}>On-site</option>
                            <option value="Tele-Therapy" {{ request('mode') == 'Tele-Therapy' ? 'selected' : '' }}>Tele-therapy</option>
                        </select>
                    </div>

                    <div class="status">
                        <label for="statusFilterSelect">Status: </label>
                        <select id="statusFilterSelect" name="status">
                            <option value="all">All</option>
                            <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                            <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>Declined</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="date-filter">
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="start_date" value="{{ request('start_date') }}">
                        
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    
                    <button type="submit">Apply Filters</button>
                    <button type="button" id="clearButton">Clear</button>
                    <input type="text" id="specificNameInput" placeholder="Search by patient or therapist name">
                </form>
            </div>
            
            <div class="table-container">
                <div id="printableTable">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Therapist</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <!--<th>Note</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->therapist->name }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $appointment->appointment_date ? $appointment->appointment_date->format('m/d/Y') : '-' }}</td>
                                    <td>{{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') : '-' }} - 
                                        {{ $appointment->end_time ? \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') : '-' }}</td>
                                    <td>
                                        @switch($appointment->mode)
                                            @case('on-site')
                                                On-site
                                                @break
                                            @case('tele-therapy')
                                                Tele-therapy
                                                @break
                                            @default
                                                {{ $appointment->mode }}
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($appointment->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('accepted')
                                                <span class="badge bg-success">Accepted</span>
                                                @break
                                            @case('finished')
                                                <span class="badge bg-info">Finished</span>
                                                @break
                                            @case('declined')
                                                <span class="badge bg-danger">Declined</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                        @endswitch
                                    </td>
                                    <!--<td>{{ $appointment->note }}</td>-->
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="button-container">
                <button class="export-btn" onclick="generatePDF()">
                    <i class="fas fa-download"></i> Export PDF
                </button>
                <button class="print-btn" onclick="printTable()">
                    <i class="fas fa-print"></i> Print Data
                </button>
            </div>
        </div>

<script>
$(document).ready(function() {
    let searchTimeout;
    let currentFilters = {
        mode: 'all',
        status: 'all',
        start_date: '',
        end_date: ''
    };

    // Real-time search functionality
    $('#specificNameInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Get current filter values
            currentFilters = {
                mode: $('#modeFilterSelect').val(),
                status: $('#statusFilterSelect').val(),
                start_date: $('#startDate').val(),
                end_date: $('#endDate').val()
            };

            // Send both search and current filters
            $.ajax({
                url: '{{ route("admin.appointmentr") }}',
                method: 'GET',
                data: {
                    search_name: $('#specificNameInput').val(),
                    mode: currentFilters.mode,
                    status: currentFilters.status,
                    start_date: currentFilters.start_date,
                    end_date: currentFilters.end_date,
                    real_time_search: true
                },
                success: function(response) {
                    updateTableContent(response.appointments);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }, 300);
    });

    // Handle form submission for filters
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        
        // Update current filters
        currentFilters = {
            mode: $('#modeFilterSelect').val(),
            status: $('#statusFilterSelect').val(),
            start_date: $('#startDate').val(),
            end_date: $('#endDate').val()
        };

        $.ajax({
            url: '{{ route("admin.appointmentr") }}',
            method: 'GET',
            data: {
                mode: currentFilters.mode,
                status: currentFilters.status,
                start_date: currentFilters.start_date,
                end_date: currentFilters.end_date,
                search_name: $('#specificNameInput').val(),
                filter_applied: true
            },
            success: function(response) {
                updateTableContent(response.appointments);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    // Function to update table content
    function updateTableContent(appointments) {
        let tableBody = '';
        appointments.forEach(function(appointment) {
            const appointmentDate = appointment.appointment_date ? new Date(appointment.appointment_date).toLocaleDateString('en-US') : '-';
            const startTime = appointment.start_time ? formatTime(appointment.start_time) : '-';
            const endTime = appointment.end_time ? formatTime(appointment.end_time) : '-';

            let statusBadge = '';
            switch(appointment.status) {
                case 'pending':
                    statusBadge = '<span class="badge bg-warning">Pending</span>';
                    break;
                case 'accepted':
                    statusBadge = '<span class="badge bg-success">Accepted</span>';
                    break;
                case 'finished':
                    statusBadge = '<span class="badge bg-info">Finished</span>';
                    break;
                case 'declined':
                    statusBadge = '<span class="badge bg-danger">Declined</span>';
                    break;
                default:
                    statusBadge = `<span class="badge bg-secondary">${appointment.status}</span>`;
            }

            let mode = appointment.mode === 'on-site' ? 'On-site' : 
                      appointment.mode === 'tele-therapy' ? 'Tele-therapy' : 
                      appointment.mode;

            tableBody += `
                <tr>
                    <td>${appointment.therapist.name}</td>
                    <td>${appointment.patient.name}</td>
                    <td>${appointmentDate}</td>
                    <td>${startTime} - ${endTime}</td>
                    <td>${mode}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;
        });
        $('.report-table tbody').html(tableBody);
    }

    function formatTime(timeString) {
        return new Date('1970-01-01T' + timeString).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    // Clear button functionality
    $('#clearButton').click(function(e) {
        e.preventDefault();
        // Reset all filters and search
        $('#modeFilterSelect').val('all');
        $('#statusFilterSelect').val('all');
        $('#startDate').val('');
        $('#endDate').val('');
        $('#specificNameInput').val('');
        currentFilters = {
            mode: 'all',
            status: 'all',
            start_date: '',
            end_date: ''
        };
        window.location.href = "{{ route('admin.appointmentr') }}";
    });
    // PDF generation functionality
    window.generatePDF = function() {
        window.jsPDF = window.jspdf.jsPDF;
        var doc = new jsPDF('l', 'mm', 'a4');
        var element = document.getElementById('printableTable');

        html2canvas(element).then(function(canvas) {
            var imgData = canvas.toDataURL('image/png');
            var imgWidth = 280;
            var pageHeight = 210;
            var imgHeight = canvas.height * imgWidth / canvas.width;
            var position = 10;

            doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            doc.save('appointment-reports.pdf');
        });
    };

    // Print functionality
    window.printTable = function() {
        window.print();
    };
});
</script>

    </body>
    </html>
</x-admin-layout>
