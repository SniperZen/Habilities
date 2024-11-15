<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Therapy Feedback</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/feedback.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>

    <div class="container">
        <h1>My Therapy Feedbacks</h1>
        <div class="controls">
            <div class="filter-buttons">
                <button class="filter-btn">
                    <span class="filter-icon">🔍</span> Filters
                </button>

                <div class="filter-buttons">
                    <form id="pendingFilterForm" action="{{ route('therapist.feedback') }}" method="GET">
                        <div class="dropdown-wrapper">
                            <button type="button" class="dropdown-btn">
                                {{ ucfirst(str_replace('_', ' ', request('pending_filter', 'all'))) }}
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
                        <input type="hidden" name="pending_filter" id="pendingFilterInput" value="{{ request('pending_filter', 'all') }}">
                        <input type="hidden" name="history_filter" value="{{ request('history_filter', 'all') }}">
                    </form>
                </div>
                <script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButton = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');
    const dropdownItems = dropdownContent.querySelectorAll('a');
    const pendingFilterForm = document.getElementById('pendingFilterForm');
    const pendingFilterInput = document.getElementById('pendingFilterInput');

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
            const filterValue = this.dataset.filter;
            
            // Update button text
            filterButton.textContent = this.textContent;
            
            // Update hidden input value
            pendingFilterInput.value = filterValue;
            
            // Submit the form
            pendingFilterForm.submit();
            
            // Close dropdown
            dropdownContent.classList.remove('open');
            filterButton.classList.remove('active');
        });
    });
});

    </script>
            </div>
        <!-- Modify the search bar input to include an ID -->
        <input type="text" id="searchInput" placeholder="Search Recipient or Title..." class="search-bar" autocomplete="off">
            <a href="{{ route('therapist.feedback2')}}"><button class="create-button">Create New</button></a>
        </div>
        <div class="table-wrapper">
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Feedback Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                    <tr class="clickable-row">
                        <td>{{ $feedback->recipient->name }}</td>
                        <td>{{ $feedback->title }}</td>
                        <td>{{ $feedback->created_at->format('m/d/Y') }}</td>
                        <td><a class="create-button views" href="{{ route('therapist.feedback3', ['id' => $feedback->id]) }}"><button class="view">View</button></a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="
                            text-align: center; 
                            padding: 20px; 
                            font-size: 16px; 
                            color: #666; 
                            background-color: #f9f9f9;
                            border: none;
                            font-family: Arial, sans-serif;
                            border-radius: 4px;
                            box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
                        ">No feedback made yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        </div>
    </div>
    <div id="toast-container"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Clickable rows functionality
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function () {
                window.location.href = this.dataset.url;
            });
        });

        // Search functionality with debounce
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('.feedback-table tbody tr');
        let searchTimeout = null;

        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                if (row.classList.contains('clickable-row')) {
                    const recipientName = row.cells[0].textContent.toLowerCase();
                    const feedbackTitle = row.cells[1].textContent.toLowerCase();

                    // Check if either recipient name or feedback title contains the search term
                    if (recipientName.includes(searchTerm) || feedbackTitle.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Show "No results found" message if no matches
            const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
            const noResultsRow = document.querySelector('.no-results-row');

            if (visibleRows.length === 0) {
                if (!noResultsRow) {
                    const tbody = document.querySelector('.feedback-table tbody');
                    const newRow = document.createElement('tr');
                    newRow.className = 'no-results-row';
                    newRow.innerHTML = '<td colspan="3">No matching results found.</td>';
                    tbody.appendChild(newRow);
                }
            } else {
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        }

        // Add loading indicator
        function createLoadingIndicator() {
            const tbody = document.querySelector('.feedback-table tbody');
            const loadingRow = document.createElement('tr');
            loadingRow.className = 'loading-row';
            loadingRow.innerHTML = '<td colspan="3"><div class="loading-spinner">Searching...</div></td>';
            tbody.appendChild(loadingRow);
        }

        function removeLoadingIndicator() {
            const loadingRow = document.querySelector('.loading-row');
            if (loadingRow) {
                loadingRow.remove();
            }
        }

        searchInput.addEventListener('input', function() {
            // Remove previous loading indicator and timeout
            removeLoadingIndicator();
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Show loading indicator if search field is not empty
            if (this.value.trim() !== '') {
                createLoadingIndicator();
            }

            // Hide all no-results messages while searching
            const noResultsRow = document.querySelector('.no-results-row');
            if (noResultsRow) {
                noResultsRow.remove();
            }

            // Set new timeout
            searchTimeout = setTimeout(() => {
                removeLoadingIndicator();
                performSearch();
            }, 500); // 500ms delay
        });
    });

    
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to show toast
        function showToast(message, type = 'success') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#28a745" : "#dc3545",
                stopOnFocus: true,
                close: true,
            }).showToast();
        }

        // Check if we just came from feedback submission
        const feedbackJustSent = localStorage.getItem('feedbackJustSent');
        if (feedbackJustSent) {
            showToast('Feedback sent successfully.', 'success');
            localStorage.removeItem('feedbackJustSent');
        }
    });
</script>
@if(session('success'))
        <script>
            showToast("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            showToast("{{ session('error') }}", 'error');
        </script>
    @endif

</body>

</html>
</x-therapist-layout>