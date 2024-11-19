<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/admin/systemfeedbackr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Document</title>
    </head>
    <body>
    <div class="reports-container" id="reports-section">
            <h1>System Feedback Reports</h1>
            <div class="report-dropdown">
                <label for="specificNameInput">Search: </label>
                <input type="text" id="specificNameInput" placeholder="Enter user name">
                <div class="date-filter">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="start_date">
                    
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="end_date">
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
                <button class="export-btn" onclick="printReport()">
                    <i class="fas fa-download"></i> Export Data
                </button>
                <!-- <button class="export-btn" onclick="downloadPDF()">
                    <i class="fas fa-download"></i> Export Data
                </button> -->
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

            // Real-time search for name input only
            let searchTimeout;
            $('#specificNameInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    updateTable();
                }, 300); // Add a small delay to prevent too many requests
            });

            // Apply Filter button for date filters
            $('#applyFilter').click(function() {
                updateTable();
            });

            // Clear all filters
            $('#clearButton').click(function() {
                $('#startDate, #endDate, #specificNameInput').val('');
                updateTable();
            });


            function printReport() {
                // Create a new window for printing
                let printWindow = window.open('', '_blank');
                let tableContent = document.getElementById('reportTable').outerHTML;
                
                // Add necessary styling
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Feedback Report</title>
                            <style>
                                table { 
                                    width: 100%; 
                                    border-collapse: collapse; 
                                }
                                th, td { 
                                    border: 1px solid black; 
                                    padding: 8px; 
                                    text-align: left; 
                                }
                                th { 
                                    background-color: #f2f2f2; 
                                }
                            </style>
                        </head>
                        <body>
                            <h1 style="text-align: center;">OTF Reports</h1>
                            ${tableContent}
                        </body>
                    </html>
                `);
                
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }

            function downloadPDF() {
                const element = document.getElementById('reportTable');
                html2canvas(element).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('l', 'mm', 'a4');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                    
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save('feedback-report.pdf');
                });
            }
        </script>
    </body>
    </html>
</x-admin-layout>
