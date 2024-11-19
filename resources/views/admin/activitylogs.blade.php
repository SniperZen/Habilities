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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

        </style>
    </head>
    <body>
        <div id="loadingPDF" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i> Generating PDF...
        </div>

        <div class="reports-container">
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
                    <input type="date" id="startDate">
                    
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate">
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
                <button class="export-btn" onclick="printReport()">
                    <i class="fas fa-download"></i> Export PDF
                </button>
                <!-- <button class="export-btn" id="exportPdfBtn" onclick="downloadPDF()">
                    <i class="fas fa-download"></i> Export PDF
                </button> -->
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
            
            // Get current filter values
            const filters = {
                usertype: $('#userFilterSelect').val(),
                activity: $('#modeFilterSelect').val(),
                start_date: $('#startDate').val(),
                end_date: $('#endDate').val(),
                search: searchTerm,
                is_search: isSearch
            };

            // Validate dates
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
        }, isSearch ? 300 : 0); // Add delay for search, immediate for filters
    }

    // Event Listeners
    $(document).ready(function() {
        // Initial load
        updateTable();

        // Real-time search
        $('#specificNameInput').on('input', function() {
            updateTable(true);
        });

        // Filter button click
        $('#applyFilter').click(function() {
            updateTable();
        });

        // Clear button click
        $('#clearButton').click(function() {
            $('#userFilterSelect').val('all');
            $('#modeFilterSelect').val('all');
            $('#startDate, #endDate').val('');
            $('#specificNameInput').val('');
            updateTable();
        });  
    });

            function printReport() {
                let printWindow = window.open('', '_blank');
                let tableContent = document.getElementById('activityTable').outerHTML;
                
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Activity Logs</title>
                            <style>
                                table { 
                                    width: 100%; 
                                    border-collapse: collapse; 
                                    margin-bottom: 20px;
                                }
                                th, td { 
                                    border: 1px solid black; 
                                    padding: 8px; 
                                    text-align: left; 
                                }
                                th { 
                                    background-color: #f2f2f2; 
                                }
                                h1 {
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .report-info {
                                    margin-bottom: 20px;
                                }
                            </style>
                        </head>
                        <body>
                            <h1>Activity Logs Report</h1>
                            <div class="report-info">
                                <p><strong>Date Generated:</strong> ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</p>
                                <p><strong>Filtered By:</strong></p>
                                <p>User Type: ${$('#userFilterSelect option:selected').text()}</p>
                                <p>Activity: ${$('#modeFilterSelect option:selected').text()}</p>
                                <p>Date Range: ${$('#startDate').val() || 'All'} to ${$('#endDate').val() || 'All'}</p>
                            </div>
                            ${tableContent}
                        </body>
                    </html>
                `);
                
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }

            async function downloadPDF() {
                $('#loadingPDF').show();
                $('.reports-container').addClass('loading');
                $('#exportPdfBtn').prop('disabled', true);

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4');

                // Store original styles
                const originalStyle = {
                    maxHeight: $('.table-container').css('max-height'),
                    overflow: $('.table-container').css('overflow')
                };

                // Temporarily remove scroll to capture full table
                $('.table-container').css({
                    'max-height': 'none',
                    'overflow': 'visible'
                });

                try {
                    // Add title and information
                    doc.setFontSize(18);
                    doc.text('Activity Logs Report', doc.internal.pageSize.getWidth() / 2, 20, { align: 'center' });
                    
                    doc.setFontSize(12);
                    doc.text(`Date Generated: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 14, 30);
                    doc.text(`Filtered By:`, 14, 37);
                    doc.text(`User Type: ${$('#userFilterSelect option:selected').text()}`, 14, 44);
                    doc.text(`Activity: ${$('#modeFilterSelect option:selected').text()}`, 14, 51);
                    doc.text(`Date Range: ${$('#startDate').val() || 'All'} to ${$('#endDate').val() || 'All'}`, 14, 58);

                    // Create canvas with full table
                    const element = document.getElementById('activityTable');
                    const canvas = await html2canvas(element, {
                        logging: false,
                        useCORS: true,
                        scrollY: -window.scrollY,
                        scale: 1.5
                    });

                    // Calculate dimensions
                    const imgWidth = doc.internal.pageSize.getWidth() - 28;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    // Add image to PDF
                    const imgData = canvas.toDataURL('image/png');
                    
                    // Handle multiple pages if needed
                    if (imgHeight > doc.internal.pageSize.getHeight() - 65) {
                        let heightLeft = imgHeight;
                        let position = 65;
                        let page = 1;
                        
                        while (heightLeft >= 0) {
                            if (page > 1) {
                                doc.addPage();
                                position = 20;
                            }
                            
                            doc.addImage(imgData, 'PNG', 14, position, imgWidth, imgHeight);
                            heightLeft -= doc.internal.pageSize.getHeight();
                            position -= doc.internal.pageSize.getHeight();
                            page++;
                        }
                    } else {
                        doc.addImage(imgData, 'PNG', 14, 65, imgWidth, imgHeight);
                    }

                    // Save the PDF
                    doc.save('activity-logs.pdf');

                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('There was an error generating the PDF. Please try again.');
                } finally {
                    // Restore original styles and remove loading state
                    $('.table-container').css(originalStyle);
                    $('#loadingPDF').hide();
                    $('.reports-container').removeClass('loading');
                    $('#exportPdfBtn').prop('disabled', false);
                }
            }
        </script>
    </body>
    </html>
</x-admin-layout>
