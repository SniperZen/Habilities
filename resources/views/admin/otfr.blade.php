<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/admin/otfr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <title>OTF Reports</title>
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
                    max-height: none;
                    overflow: visible;
                }
            }
        </style>
    </head>
    <body>
        <div class="reports-container">
            <h1>OTF Reports</h1>
            <div class="report-dropdown">
                <form action="{{ route('admin.otfr') }}" method="GET" id="filterForm">
                    <div class="date-filter">
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="start_date" value="{{ request('start_date') }}">
                        
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    
                    <button type="submit">Apply Filters</button>
                    <button type="button" id="clearButton">Clear</button>
                </form>
            </div>

            <div class="table-container">
                <div id="printableTable">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Therapist</th>
                                <th>Patient</th>
                                <th>Diagnosis</th>
                                <th>Feedback Title</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks as $feedback)
                                <tr>
                                    <td>{{ $feedback->sender->name }}</td>
                                    <td>{{ $feedback->recipient->name }}</td>
                                    <td>{{ $feedback->diagnosis }}</td>
                                    <td>{{ $feedback->title }}</td>
                                    <td>{{ $feedback->created_at->format('m/d/Y') }}</td>
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
            // Clear button functionality
            document.getElementById('clearButton').addEventListener('click', function() {
                document.getElementById('startDate').value = '';
                document.getElementById('endDate').value = '';
                document.getElementById('filterForm').submit();
            });

            // PDF generation functionality
            function generatePDF() {
                window.jsPDF = window.jspdf.jsPDF;
                var doc = new jsPDF('l', 'mm', 'a4');

                var element = document.getElementById('printableTable');

                html2canvas(element, {
                    useCORS: true,
                    allowTaint: true,
                    scale: 2,
                    logging: false,
                    onclone: function(clonedDoc) {
                        var elements = clonedDoc.getElementsByClassName('report-table');
                        for(var i = 0; i < elements.length; i++) {
                            elements[i].style.width = '100%';
                        }
                    }
                }).then(function(canvas) {
                    var imgData = canvas.toDataURL('image/png');
                    var imgWidth = 280;
                    var pageHeight = 210;
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;
                    var position = 10;

                    doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    doc.save('otf-reports.pdf');
                });
            }

            // Print functionality
            function printTable() {
                window.print();
            }
        </script>
    </body>
    </html>
</x-admin-layout>
