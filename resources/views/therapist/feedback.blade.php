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
        <input type="text" id="searchInput" placeholder="Search by Recipient, Title or Diagnosis..." class="search-bar" autocomplete="off">
            <a href="{{ route('therapist.feedback2')}}"><button class="create-button">Create New</button></a>
        </div>
        <div class="table-wrapper">
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Feedback Title</th>
                    <th>Diagnosis</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->recipient->name }}</td>
                        <td>{{ $feedback->title }}</td>
                        <td>{{ $feedback->diagnosis }}</td>
                        <td>{{ $feedback->created_at->format('m/d/Y') }}</td>
                        <td><a class="create-button views" href="{{ route('therapist.feedback3', ['id' => $feedback->id]) }}"><button class="view">Expand</button></a></td>
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
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('.feedback-table tbody');
    let searchTimeout = null;

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const tableRows = tableBody.querySelectorAll('tr:not(.no-results-row):not(.loading-row)');
        let hasVisibleRows = false;

        // Remove existing no-results row if present
        removeNoResultsRow();

        tableRows.forEach(row => {
            const cells = row.cells;
            if (cells.length >= 3) {
                const recipientName = cells[0].textContent.toLowerCase();
                const feedbackTitle = cells[1].textContent.toLowerCase();
                const diagnosis = cells[2].textContent.toLowerCase();

                if (searchTerm === '' || 
                    recipientName.includes(searchTerm) || 
                    feedbackTitle.includes(searchTerm) || 
                    diagnosis.includes(searchTerm)) {
                    row.style.display = '';
                    hasVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Show "No results found" message if needed
        if (!hasVisibleRows && searchTerm !== '') {
            showNoResultsMessage();
        }
    }

    function showNoResultsMessage() {
        const noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        noResultsRow.innerHTML = `
            <td colspan="5" style="
                text-align: center;
                padding: 20px;
                color: #666;
                background-color: #f9f9f9;
                font-style: italic;
            ">
                No matching results found
            </td>
        `;
        tableBody.appendChild(noResultsRow);
    }

    function removeNoResultsRow() {
        const noResultsRow = tableBody.querySelector('.no-results-row');
        if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    function createLoadingIndicator() {
        removeLoadingIndicator(); // Remove existing if any
        const loadingRow = document.createElement('tr');
        loadingRow.className = 'loading-row';
        loadingRow.innerHTML = `
            <td colspan="5" style="
                text-align: center;
                padding: 15px;
                background-color: #f5f5f5;
            ">
                <div class="loading-spinner">
                    Searching...
                </div>
            </td>
        `;
        tableBody.appendChild(loadingRow);
    }

    function removeLoadingIndicator() {
        const loadingRow = tableBody.querySelector('.loading-row');
        if (loadingRow) {
            loadingRow.remove();
        }
    }

    // Debounced search handler
    function handleSearch() {
        removeLoadingIndicator();
        
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        const searchValue = searchInput.value.trim();
        
        if (searchValue !== '') {
            createLoadingIndicator();
        }

        searchTimeout = setTimeout(() => {
            removeLoadingIndicator();
            performSearch();
        }, 300); // Reduced delay for better responsiveness
    }

    // Add event listener for search input
    searchInput.addEventListener('input', handleSearch);

    // Initial search if there's a value in the search input
    if (searchInput.value.trim() !== '') {
        performSearch();
    }

    // Add this style to your CSS
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            display: inline-block;
            position: relative;
            color: #666;
        }
        
        .no-results-row td {
            transition: all 0.3s ease;
        }
        
        .feedback-table tr {
            transition: opacity 0.2s ease;
        }
    `;
    document.head.appendChild(style);
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