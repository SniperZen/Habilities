<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/admin/inquiryr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Inquiry Reports</title>
        <style>
            .document-link {
                text-decoration: none;
                color: black;
            }
            
            .document-link:hover {
                text-decoration: underline;
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
                .document-link {
                    text-decoration: none !important;
                }
                a[href]::after {
                    content: none !important;
                }
            }

            .button-container {
                display: flex;
                gap: 10px;
                margin-top: 20px;
            }

            .export-btn {
                padding: 10px 20px;
                cursor: pointer;
                border: none;
                border-radius: 5px;
                display: flex;
                align-items: center;
                gap: 5px;
            }
        </style>
    </head>
    <body>
        <div class="reports-container">
            <div class="int">
            <h1>Inquiry Reports</h1>
            <div class="report-dropdown">
                <form action="{{ route('admin.inquiryr') }}" method="GET" id="filterForm">
                    <label for="specificNameInput">Search: </label>
                    <input type="text" id="specificNameInput" placeholder="Search by patient name">
                    <div class="mode">
                        <label for="status">Status: </label>
                        <select id="status" name="status">
                            <option value="all">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="date-filter">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    
                    <button class="filt" type="submit" name="submit" value="1">Apply Filters</button>
                    <button type="button" id="clearButton">Clear</button>
                </form>
            </div>

            <div id="printableTable">
            <div class="table-container">
                <table class="report-table">
                    <!-- Your existing table content -->
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Clinical / Working Diagnosis</th>
                            <!--<th>Elaboration</th>-->
                            <th>Attached Documents</th>
                            <th>Date Received</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                        <tr>
                            <td>{{ $inquiry->user->name }}</td>
                            <td>{{ $inquiry->concerns }}</td>
                            <!--<td>{{ $inquiry->elaboration }}</td>-->
                            <td>
                                <div class="out">
                                <div class="cont">
                                @if($inquiry->identification_card)
                                    <div>
                                        <a href="{{ Storage::url($inquiry->identification_card) }}" target="_blank" class="document-link">
                                            <i class="fas fa-file-pdf"></i> ID Card
                                        </a>
                                    </div>
                                @endif
                                @if($inquiry->birth_certificate)
                                    <div>
                                        <a href="{{ Storage::url($inquiry->birth_certificate) }}" target="_blank" class="document-link">
                                            <i class="fas fa-file-pdf"></i> Birth Certificate
                                        </a>
                                    </div>
                                @endif
                                @if($inquiry->diagnosis_reports)
                                    <div>
                                        <a href="{{ Storage::url($inquiry->diagnosis_reports) }}" target="_blank" class="document-link">
                                            <i class="fas fa-file-pdf"></i> Diagnosis Report
                                        </a>
                                    </div>
                                @endif
                                </div>
                                </div>
                            </td>
                            <td>{{ $inquiry->created_at->format('m/d/Y') }}</td>
                            <td>{{ $inquiry->completed_at ? 'Completed' : 'Pending' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
            <div class="button-container">
                <!-- <button class="export-btn" onclick="generatePDF()">
                    <i class="fas fa-download"></i> Export PDF
                </button> -->
                <button class="print-btn" onclick="printTable()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>
        </div>
    </div>
        <script>
$(document).ready(function() {
    let searchTimeout;

    // Real-time search functionality
    $('#specificNameInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Only send search parameter for real-time search
            $.ajax({
                url: '{{ route("admin.inquiryr") }}',
                method: 'GET',
                data: {
                    search_name: $('#specificNameInput').val()
                },
                success: function(response) {
                    updateTableContent(response.inquiries);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }, 300);
    });

    // Clear button functionality
    $('#clearButton').click(function(e) {
        e.preventDefault();
        // Clear all inputs
        $('#specificNameInput').val('');
        $('#status').val('all');
        $('#start_date').val('');
        $('#end_date').val('');
        window.location.href = "{{ route('admin.inquiryr') }}?clear=1";
    });

    // Function to update table content
    function updateTableContent(inquiries) {
        let tableBody = '';
        inquiries.forEach(function(inquiry) {
            const createdAt = new Date(inquiry.created_at);
            const formattedDate = (createdAt.getMonth() + 1).toString().padStart(2, '0') + 
                                '/' + createdAt.getDate().toString().padStart(2, '0') + 
                                '/' + createdAt.getFullYear();
            
            let documents = '';
            if (inquiry.identification_card) {
                documents += `<div><a href="/storage/${inquiry.identification_card}" target="_blank" class="document-link">
                    <i class="fas fa-file-pdf"></i> ID Card</a></div>`;
            }
            if (inquiry.birth_certificate) {
                documents += `<div><a href="/storage/${inquiry.birth_certificate}" target="_blank" class="document-link">
                    <i class="fas fa-file-pdf"></i> Birth Certificate</a></div>`;
            }
            if (inquiry.diagnosis_reports) {
                documents += `<div><a href="/storage/${inquiry.diagnosis_reports}" target="_blank" class="document-link">
                    <i class="fas fa-file-pdf"></i> Diagnosis Report</a></div>`;
            }

            tableBody += `
                <tr>
                    <td>${inquiry.user.name}</td>
                    <td>${inquiry.concerns}</td>
                    <td>${documents}</td>
                    <td>${formattedDate}</td>
                    <td>${inquiry.completed_at ? 'Completed' : 'Pending'}</td>
                </tr>
            `;
        });
        $('.report-table tbody').html(tableBody);
    }

    // Handle form submission for filters
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        
        // Send all filter parameters when form is submitted
        $.ajax({
            url: '{{ route("admin.inquiryr") }}',
            method: 'GET',
            data: {
                status: $('#status').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                search_name: $('#specificNameInput').val(),
                filter_applied: true
            },
            success: function(response) {
                updateTableContent(response.inquiries);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    // PDF Generation function
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
            doc.save('inquiry-reports.pdf');
        });
    };

    // Print function
    window.printTable = function() {
        window.print();
    };
});

        </script>

    </body>
    </html>
</x-admin-layout>
