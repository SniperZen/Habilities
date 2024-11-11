<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/AppSched.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .dropdown-content {
            display: none;
        }
        .dropdown-content.show {
            display: block;
        }
    </style>
</head>
<body>
    <main class="content">
<div class="ahSort">
            <div class="section-title">Appointment History</div>
            <div class="filter-buttons2">
                        <button class="filter-btn2">
                            <span class="filter-icon2">🔍</span> Filters
                        </button>
                        <form id="historyFilterForm" action="{{ route('therapist.myHistory') }}" method="GET">
                            <div class="dropdown-wrapper">
                                <button type="button" class="dropdown-btn">
                                    {{ ucfirst(str_replace('_', ' ', request('history_filter', 'all'))) }}
                                </button>
                                <div class="dropdown-content">
                                    <a href="#" data-filter="today">Today</a>
                                    <a href="#" data-filter="yesterday">Yesterday</a>
                                    <a href="#" data-filter="last_7_days">Last 7 Days</a>
                                    <a href="#" data-filter="last_14_days">Last 14 Days</a>
                                    <a href="#" data-filter="last_21_days">Last 21 Days</a>
                                    <a href="#" data-filter="last_28_days">Last 28 Days</a>
                                    <a href="#" data-filter="all">All</a>
                                </div>
                            </div>
                            <input type="hidden" name="history_filter" id="historyFilterInput" value="{{ request('history_filter', 'all') }}">
                        </form>
                </div>
        </div>
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Mode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if($historyAppointments->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; font-size: 16px; color: #666; background-color: #f9f9f9;">No appointment history found.</td>
                    </tr>
                @else
                    @foreach($historyAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                            <td>
                                @if($appointment->status == 'declined')
                                    -
                                @else
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                @endif
                            </td>
                            <td>{{ $appointment->mode }}</td>
                            <td>
                                @if($appointment->status == 'finished')
                                    <span>Finished</span>
                                @else
                                    <span>Cancelled</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownLinks = document.querySelectorAll('.dropdown-content a');
    const filterForm = document.getElementById('historyFilterForm');
    const filterInput = document.getElementById('historyFilterInput');
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    // Toggle dropdown visibility
    dropdownBtn.addEventListener('click', function(e) {
        e.preventDefault();
        dropdownContent.classList.toggle('show');
    });

    // Close the dropdown if clicked outside
    window.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-btn')) {
            if (dropdownContent.classList.contains('show')) {
                dropdownContent.classList.remove('show');
            }
        }
    });

    dropdownLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            filterInput.value = filter;
            dropdownBtn.textContent = this.textContent;
            dropdownContent.classList.remove('show');
            filterForm.submit();
        });
    });
});
</script>
</main>
</body>
</html>
</x-therapist-layout>