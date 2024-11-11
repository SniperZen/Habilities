<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/AppReq.css') }}">
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButton = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');
    const dropdownItems = dropdownContent.querySelectorAll('a');
    const filterForm = document.getElementById('filterForm');
    const filterInput = document.getElementById('filterInput');

    filterButton.addEventListener('click', function () {
        dropdownContent.classList.toggle('open');
        filterButton.classList.toggle('active');
    });

    document.addEventListener('click', function (event) {
        if (!filterButton.contains(event.target) && !dropdownContent.contains(event.target)) {
            dropdownContent.classList.remove('open');
            filterButton.classList.remove('active');
        }
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            const filter = this.getAttribute('data-filter');
            filterButton.textContent = this.textContent;
            filterInput.value = filter;
            dropdownContent.classList.remove('open');
            filterButton.classList.remove('active');
            filterForm.submit();
        });
    });
});

    </script>
</head>
<body>
        <div class="content">
            <div class="tabs">
                <div class="tabi">
                    <div class="tab active">Appointment Requests</div>
                    <a href="{{ route('therapist.AppSched') }}"><div class="tab tab1">Appointment Schedule</div></a>
                    </div>
                </div>

            <div class="arSort">
                <div class="section-title"><h2>Appointment Requests</h2></div>
                    <div class="filter-buttons">
                            <button class="filter-btn">
                                <span class="filter-icon">🔍</span> Filters
                            </button>

                            <form id="filterForm" action="{{ route('therapist.AppReq') }}" method="GET">
                                <div class="dropdown-wrapper">
                                    <button type="button" class="dropdown-btn">
                                        {{ request('filter') ? ucfirst(str_replace('_', ' ', request('filter'))) : 'All' }}
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
                                <input type="hidden" name="filter" id="filterInput" value="{{ request('filter', 'all') }}">
                            </form>
                     </div>
            </div>
            <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Received</th>
                        <th>Mode</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($appointments->isEmpty())
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">No appointment requests found</td>
                        </tr>
                    @else
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->first_name }} {{ $appointment->middle_name }} {{ $appointment->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('F j, Y') }}</td>
                            <td>{{ $appointment->mode }}</td>
                            <td>{{ Str::limit($appointment->note ?? '-', 50) }}</td> <!-- Truncated message -->
                            <td>
                                <a href="{{ route('therapist.AppReq2', $appointment->id) }}">
                                    <button class="review-button">Review</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <style>
            table td[colspan="5"] {
    font-size: 16px;
    color: #666;
    background-color: #f9f9f9;
    border: none;
}
</style>
            </div>
        </div>
    </div>
</body>
</html>
</x-therapist-layout>