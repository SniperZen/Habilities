<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $feedback->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/feedback3.css') }}">
</head>
<body>

<main class="main-content">
    <header class="content-header">
        <h1>Therapy Feedback</h1>
    </header>
    <div class="header-actions">
        <div class="icon-container">
            <a href="{{ route('therapist.feedback') }}">
                <img src="{{ asset('images/icons/back.png') }}" alt="Back">
            </a>
        </div>
        <div class="icon-container">
            <img src="{{ asset('images/icons/message.png') }}" alt="Edit">
        </div>
        <div class="icon-container">
            <img src="{{ asset('images/icons/delete.png') }}" alt="Delete" id="delete-icon">
        </div>
    </div>
    <header class="inquiry-header">
        <div class="profile-info">
            <img src="{{ Storage::url($feedback->recipient->profile_image ?? 'default.png') }}" alt="Profile Image" class="profile-pic">
            <div class="profile-details">
                <h3>{{ $feedback->recipient->name }}</h3>
                <p>Patient ID: <strong>{{ sprintf('P-%04d', $feedback->recipient->id) }}</strong></p>
            </div>
        </div>
        <p class="timestamp">{{ $feedback->recipient->created_at->format('F d, Y, h:i A') }}</p>
    </header>

    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
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

    <section class="inquiry-details">
        <h4>Feedback Title: {{ $feedback->title }}</h4>
        <p><strong>Feedback Content:</strong></p>
        <p>{!! $feedback->content !!}</p>    
    </section>
</main>

<form id="delete-form" action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
window.onload = function () {
    const modal = document.getElementById("delete-modal");
    const deleteBtn = document.getElementById("delete-icon");
    const cancelBtn = document.getElementById("cancel-btn");
    const confirmBtn = document.getElementById("confirm-btn");

    deleteBtn.onclick = function() {
        modal.style.display = "block";
    }

    cancelBtn.onclick = function() {
        modal.style.display = "none";
    }

    confirmBtn.onclick = function() {
        document.getElementById('delete-form').submit();
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