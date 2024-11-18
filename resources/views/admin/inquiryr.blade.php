<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/admin/inquiryr.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <title>Document</title>
    </head>
    <body>
    <div class="reports-container">
            <h1>Inquiry Reports</h1>
            <div class="report-dropdown">
                <!-- <div class= "user">
                    <label for="userFilterSelect">User: </label>
                    <select id="userFilterSelect">
                        <option value="all">All User Types</option>
                        <option value="user">Patient</option>
                        <option value="therapist">Therapist</option>
                        <option value="specificName">Search User Name</option>
                    </select>
                </div> -->

                <div class="mode">
                    <label for="modeFilterSelect">Diagnosis: </label>
                    <select id="modeFilterSelect">
                        <option value="all">All</option>
                        <option value="user">Face to face</option>
                        <option value="therapist">Tele-Therapy</option>
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
                        <th>Patient Name</th>
                        <th>Clinical / Working Diagnosis</th>
                        <th>Date Received</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Celestine E. Guitierrez</td>
                        <td>Autism</td>
                        <td>04/19/2024</td>
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