
        @php
            use Illuminate\Support\Str;
            use Carbon\Carbon;
        @endphp
<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/inquiry.css')}}">
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>

    <!-- Main Content -->
    <main class="content">
        <div class="inners">
        <header class="content-header">
            <h1>Inquiries</h1>
        </header>

        <section class="inquiry-table">
        <div class="head-cont">
        <h2>Pending Inquiries</h2>
            <div class="filter-buttons">
                <div>
                    <button class="filter-btn">
                        <span class="filter-icon">🔍</span> Filters
                    </button>
                </div>

                <div class="filter-buttons">
                    <form id="pendingFilterForm" action="{{ route('therapist.inquiry') }}" method="GET">
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

            </div>
            </div>
            <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Clinical / Working Diagnosis</th>
                        <th>Description</th>
                        <th>Received</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingInquiries as $inquiry)
                        @if(is_null($inquiry->completed_at))
                            <tr>
                                <td>
                                    @if($inquiry->user)
                                        {{ $inquiry->user->first_name }} {{ $inquiry->user->middle_name }} {{ $inquiry->user->last_name }}
                                    @else
                                        No user found
                                    @endif
                                </td>
                                <td>{{ ucfirst($inquiry->concerns) }}</td>
                                <td>{{ Str::limit($inquiry->elaboration, 80) }}</td>
                                <td>{{ $inquiry->created_at->format('m/d/Y') }}</td>
                                <td style="display:flex;     align-items: center; justify-content: center;">
                                    <form method="POST" action="{{ route('inquiry.complete', ['id' => $inquiry->id]) }}">
                                        @csrf
                                        <button type="submit" class="complete-btn">Complete</button>
                                    </form>
                                    <a href="{{ route('inquiry.message', ['id' => $inquiry->id]) }}"><button class="view">View</button></a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" style="
                                text-align: center; 
                                padding: 20px; 
                                font-size: 16px; 
                                color: #666; 
                                background-color: #f9f9f9;
                                border: none;
                                font-family: Arial, sans-serif;
                                border-radius: 4px;
                                box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
                            ">No pending inquiries.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            </div>

        </section>
        <section class="inquiry-history-table">
            <header class="content-header">
                <h2>Inquiry History</h2>
                <div class="head-cont2">
                <button class="filter-btn">
                        <span class="filter-icon">🔍</span> Filters
                    </button>
                <form id="historyFilterForm" action="{{ route('therapist.inquiry') }}" method="GET">
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
                    <input type="hidden" name="pending_filter" value="{{ request('pending_filter', 'all') }}">
                </form>
                </div>
            </header>
            <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Clinical / Working Diagnosis</th>
                        <th>Description</th>
                        <th>Completed</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedInquiries as $inquiry)
                        <tr>
                            <td>
                                @if($inquiry->user)
                                    {{ $inquiry->user->first_name }} {{ $inquiry->user->middle_name }} {{ $inquiry->user->last_name }}
                                @else
                                    No user found
                                @endif
                            </td>
                            <td>{{ ucfirst($inquiry->concerns) }}</td>
                            <td>{{ Str::limit($inquiry->elaboration, 80) }}</td>
                            <td>{{ $inquiry->completed_at ? Carbon::parse($inquiry->completed_at)->format('m/d/Y') : 'N/A' }}</td>
                            <td>Completed</td>
                            <td><a href="{{ route('inquiry.message', ['id' => $inquiry->id]) }}"><button class="view">View</button></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="
                                text-align: center; 
                                padding: 20px; 
                                font-size: 16px; 
                                color: #666; 
                                background-color: #f9f9f9;
                                border: none;
                                font-family: Arial, sans-serif;
                                border-radius: 4px;
                                box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
                            ">No completed inquiries in history.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            </div>
        </section>
        </div>

    </main>

    <!-- Confirmation Modal -->
<div id="confirmationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Confirm Completion</h2>
                </div>
                <div class="bot">
                    <p>Are you sure you want to mark this inquiry as completed?</p>
                </div>
            </div>
            <div class="modal-buttons">
                <button id="cancelComplete" class="cancel-btn">Cancel</button>
                <button id="confirmComplete" class="confirm-btn">Yes</button>
            </div>
        </div>
    </div>
</div>

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter setup function
    function setupFilter(formId, btnClass, contentClass, inputId) {
        const form = document.getElementById(formId);
        const filterButton = form.querySelector(`.${btnClass}`);
        const dropdownContent = form.querySelector(`.${contentClass}`);
        const dropdownItems = dropdownContent.querySelectorAll('a');
        const filterInput = document.getElementById(inputId);

        filterButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
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
                form.submit();
            });
        });
    }

    // Setup filters for both sections
    setupFilter('pendingFilterForm', 'dropdown-btn', 'dropdown-content', 'pendingFilterInput');
    setupFilter('historyFilterForm', 'dropdown-btn', 'dropdown-content', 'historyFilterInput');

    // Create and append modal to body
    const modal = document.createElement('div');
    modal.innerHTML = `
        <div id="confirmationModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Confirm Completion</h2>
                <p>Are you sure you want to mark this inquiry as completed?</p>
                <div class="modal-buttons">
                    <button id="confirmComplete" class="confirm-btn">Yes, Complete</button>
                    <button id="cancelComplete" class="cancel-btn">Cancel</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Modal elements
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmBtn = document.getElementById('confirmComplete');
    const cancelBtn = document.getElementById('cancelComplete');
    let currentForm = null;
    let currentRow = null;

    // Table row click functionality
    const rows = document.querySelectorAll('.inquiry-table tbody tr, .inquiry-history-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('click', function (e) {
            // Don't navigate if clicking the complete button
            if (e.target.closest('.complete-btn')) return;
            
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });

    // Complete button functionality
    const completeButtons = document.querySelectorAll('.complete-btn');
    completeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();

            currentForm = this.closest('form');
            currentRow = this.closest('tr');
            confirmationModal.style.display = 'block';
            confirmationModal.classList.add('show');
        });
    });

// Update your confirm completion handler
confirmBtn.addEventListener('click', function() {
    if (currentForm) {
        const formData = new FormData(currentForm);
        
        fetch(currentForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            confirmationModal.style.display = 'none';
            confirmationModal.classList.remove('show');
            
            if (data.success) {
                // Store the success message in sessionStorage before reload
                sessionStorage.setItem('toastMessage', data.message || "Inquiry marked as completed");
                sessionStorage.setItem('toastType', 'success');
                window.location.reload();
            } else {
                showToast(data.message || "Failed to complete inquiry", 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            sessionStorage.setItem('toastMessage', "An error occurred. Please try again.");
            sessionStorage.setItem('toastType', 'error');
            window.location.reload();
        });
    }
});

    // Cancel button handler
    cancelBtn.addEventListener('click', function() {
        confirmationModal.style.display = 'none';
        confirmationModal.classList.remove('show');
        currentForm = null;
        currentRow = null;
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === confirmationModal) {
            confirmationModal.style.display = 'none';
            confirmationModal.classList.remove('show');
            currentForm = null;
            currentRow = null;
        }
    });

    // Add ESC key handler for modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && confirmationModal.style.display === 'block') {
            confirmationModal.style.display = 'none';
            confirmationModal.classList.remove('show');
            currentForm = null;
            currentRow = null;
        }
    });
});

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
// Add this to check for and display toast messages after page load
document.addEventListener('DOMContentLoaded', function() {
    // Check for toast message in session storage
    const toastMessage = sessionStorage.getItem('toastMessage');
    const toastType = sessionStorage.getItem('toastType');
    
    if (toastMessage) {
        showToast(toastMessage, toastType);
        // Clear the message after showing
        sessionStorage.removeItem('toastMessage');
        sessionStorage.removeItem('toastType');
    }
});
</script>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("{{ session('success') }}", 'success');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("{{ session('error') }}", 'error');
        });
    </script>
@endif

</html>
</x-therapist-layout>