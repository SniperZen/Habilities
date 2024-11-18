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
        <title>Inquiry Reports</title>
        <style>
            .document-link {
                text-decoration: none;
                color: black;
            }
            
            .document-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="reports-container">
            <h1>Inquiry Reports</h1>
            <div class="report-dropdown">
                <form action="{{ route('admin.inquiryr') }}" method="GET" id="filterForm">
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
                    
                    <button type="submit">Apply Filters</button>
                    <button type="button" id="clearButton">Clear</button>
                </form>
            </div>

            <div id="printableTable">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Clinical / Working Diagnosis</th>
                            <th>Elaboration</th>
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
                            <td>{{ $inquiry->elaboration }}</td>
                            <td>
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
                            </td>
                            <td>{{ $inquiry->created_at->format('m/d/Y') }}</td>
                            <td>{{ $inquiry->completed_at ? 'Completed' : 'Pending' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button class="export-btn" onclick="generatePDF()">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>

        <script>
            document.getElementById('clearButton').addEventListener('click', function() {
                document.getElementById('status').value = 'all';
                document.getElementById('start_date').value = '';
                document.getElementById('end_date').value = '';
                document.getElementById('filterForm').submit();
            });

            function generatePDF() {
                // Create new jsPDF instance
                window.jsPDF = window.jspdf.jsPDF;
                var doc = new jsPDF('l', 'mm', 'a4');

                // Get the element
                var element = document.getElementById('printableTable');

                // Use html2canvas to convert the element to canvas
                html2canvas(element).then(function(canvas) {
                    var imgData = canvas.toDataURL('image/png');
                    var imgWidth = 280; // A4 width in mm
                    var pageHeight = 210;  // A4 height in mm
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;
                    var position = 10;

                    doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    doc.save('inquiry-reports.pdf');
                });
            }
        </script>
    </body>
    </html>
</x-admin-layout>
