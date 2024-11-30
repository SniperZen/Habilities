<x-patient-layout>
    <div class="container">
        <main class="main-content">
            <link rel="stylesheet" href="{{ asset('css/patient/appntmnt.css')}}">

            <style>    
                .empty-table-message {
                    text-align: center;
                    padding: 20px;
                    font-size: 1.1em;
                    color: #666;
                    background-color: #f9f9f9;
                }
                
            </style>
            <!-- Appointment History -->
            <div class="top-bar">
                <div>
                    <h2>Appointment History</h2>
                </div>
                <div>
                    <div class="filter-buttons">
                        <button class="filter-btn">
                            <span class="filter-icon">🔍</span> Filters
                        </button>

                        <form id="historyFilterForm" action="{{ route('patient.myHistory') }}" method="GET">
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
            </div>
            <div class="table">
            <table class="history">
    <thead>
        <tr>
            <th>Therapist Name</th>
            <th>Appointment Date</th>
            <th>Time</th>
            <th>Completion Date</th>
            <th>Mode</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @if($pastAppointments->isEmpty())
        <tr>
            <td colspan="6" class="empty-table-message">
                No appointments found.
            </td>
        </tr>
    @else
        @foreach($pastAppointments as $appointment)
            <tr>
                <td>{{ $appointment->therapist->name ?? 'N/A' }}</td>
                <td>
                    @if($appointment->status == 'pending')
                        -
                    @else
                        {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') : '-' }}
                    @endif
                </td>
                <td>
                    @if($appointment->status == 'pending')
                        -
                    @else
                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                    @endif
                </td>
                <td>
                    @if($appointment->completion_date)
                        {{ \Carbon\Carbon::parse($appointment->completion_date)->format('F j, Y g:i A') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ ucfirst($appointment->mode) }}</td>
                <td>
                    <span class="badge 
                        @switch(strtolower(str_replace('_', ' ', $appointment->status)))
                            @case('finished')
                                bg-success
                                @break
                            @case('missed')
                                bg-warning
                                @break
                            @case('therapist declined')
                            @case('therapist canceled')
                            @case('patient declined')
                            @case('patient canceled')
                                bg-danger
                                @break
                            @default
                                bg-secondary
                        @endswitch
                    ">
                        {{ ucwords(str_replace('_', ' ', $appointment->status)) }}
                    </span>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>


            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupFilter(formId, btnClass, contentClass, inputId) {
            const form = document.getElementById(formId);
            const filterButton = form.querySelector(`.${btnClass}`);
            const dropdownContent = form.querySelector(`.${contentClass}`);
            const dropdownItems = dropdownContent.querySelectorAll('a');
            const filterInput = document.getElementById(inputId);

            filterButton.addEventListener('click', function (event) {
                event.preventDefault();
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
                    form.submit();
                });
            });
        }

        // Setup filter for history section
        setupFilter('historyFilterForm', 'dropdown-btn', 'dropdown-content', 'historyFilterInput');
    });
    </script>
</x-patient-layout>
