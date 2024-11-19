@php
        use Carbon\Carbon;
        $age = Carbon::parse($inquiry->user->date_of_birth)->age;
        $formattedDateOfBirth = Carbon::parse($inquiry->user->date_of_birth)->format('F j, Y');
    @endphp

<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/inquirymess.css')}}">
</head>
<body>

<main class="main-content">
    <div class="scroller">
    <header class="content-header">
    </header>
    <div class="header-actions">
        <div class="icon-container">
            <a href="{{ route('therapist.inquiry') }}">
                <img src="{{ asset('images/icons/back.png') }}" alt="Back">
            </a>
            <h1>Inquiries</h1>
        </div>
        
        <!--<div class="icon-container">
            <img src="{{ asset('images/icons/message.png') }}" alt="Edit">
        </div>-->
        <div class="icon-container">
            <img src="{{ asset('images/icons/delete.png') }}" alt="Delete" id="delete-icon">
        </div>
    </div>
    <header class="inquiry-header">
        <div class="profile-info">
            <img src="{{ Storage::url($inquiry->user->profile_image) }}" alt="Profile Image" class="profile-pic">
            <div class="profile-details">
                <h3>{{ $inquiry->user->first_name }} {{ $inquiry->user->middle_name }} {{ $inquiry->user->last_name }}</h3>
                <p>Patient ID: <strong>{{ sprintf('P-%04d', $inquiry->user->id) }}</strong></p>
            </div>
        </div>
        <p class="timestamp">{{ $inquiry->created_at->format('F d, Y, h:i A') }}</p>
    </header>

    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <span class="close">&times;</span>
                        <h2>Delete Message</h2>
                    </div>
                    <div class="bot">
                        <p>You are about to delete an Inquiry Message from a Patient. Are you sure you want to continue?</p>
                    </div>
                </div>
                <div class="modal-actions modal-buttons">
                    <button id="cancel-btn" class="cancel-btn">Cancel</button>
                    <button id="confirm-btn" class="confirm-btn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-form" action="{{ route('inquiry.delete', $inquiry->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <section class="inquiry-details">
        <h4>Inquiry Details:</h4>
        <div class="details-grid">
            <div><strong>Age:</strong> {{ $age }}</div>
            <div><strong>Contact No.:</strong> {{ $inquiry->user->contact_number }}</div>
            <div><strong>Birthday:</strong> {{ $formattedDateOfBirth }}</div>
            <div><strong>Gender:</strong> {{ ucfirst($inquiry->user->gender) }}</div>
            <div><strong>Clinical/Working Diagnosis:</strong> {{ ucfirst($inquiry->concerns) }}</div>
            <div><strong>Address:</strong> {{ ucfirst($inquiry->user->home_address) }}</div>
            <div><strong>Elaboration:</strong> <br> {{ $inquiry->elaboration }}</div>
        </div>
        
        <h4 class="h4">Attached Documents:</h4>
        <ul class="attached-docs">
            <li>
                <img src="{{ asset('images/icons/pdf.png') }}" alt="PDF Icon">
                <a href="{{ asset('storage/' . $inquiry->identification_card) }}" target="_blank">Identification Card</a>
            </li>
            <li>
                <img src="{{ asset('images/icons/pdf.png') }}" alt="PDF Icon">
                <a href="{{ asset('storage/' . $inquiry->birth_certificate) }}" target="_blank">Birth Certificate</a>
            </li>
            <li>
                <img src="{{ asset('images/icons/pdf.png') }}" alt="PDF Icon">
                <a href="{{ asset('storage/' . $inquiry->diagnosis_reports) }}" target="_blank">Diagnosis Reports</a>
            </li>
        </ul>
    </section>

    <div class="send-message">
    <a href="{{ route('therapist.chat', ['id' => $inquiry->user->id]) }}">
        <button>Send a Message</button>
    </a>
</div>

</div>
</main>

<script>
    window.onload = function () {
        const modal = document.getElementById("delete-modal");
        const deleteBtn = document.getElementById("delete-icon");
        const closeBtn = document.querySelector(".close");
        const cancelBtn = document.getElementById("cancel-btn");
        const confirmBtn = document.getElementById("confirm-btn");

        deleteBtn.onclick = function() {
            modal.style.display = "block";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        cancelBtn.onclick = function() {
            modal.style.display = "none";
        }

        confirmBtn.onclick = function() {
            document.getElementById("delete-form").submit();
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
</script>

</body>
</html>

</x-therapist-layout>
