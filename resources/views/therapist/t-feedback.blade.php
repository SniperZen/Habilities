<x-therapist-layout>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="{{ asset('css/therapist/p-feedback.css') }}">
      <title>Feedback</title>
  </head>
  <body>
      <div class="feedback-content">
          <div class="feedback-container">
              <h1>We Value Your Feedback!</h1>
              <p>Your feedback and ideas are essential in helping us improve our systems. Please share your thoughts below.</p>
              
              @if (session('success'))
                  <div class="alert alert-success">
                      {{ session('success') }}
                  </div>
              @endif

              <form id="feedbackForm" action="{{ route('therapist.feedback.stores') }}" method="POST">
                  @csrf
                  <h3>How was your experience with our service?</h3>
                  <textarea name="feedback" id="feedbackText" placeholder="Type here..." required></textarea>
                  @error('feedback')
                      <span class="error">{{ $message }}</span>
                  @enderror
                  <button type="button" onclick="showConfirmationModal()">Submit</button>
              </form>
          </div>
      </div>

      <!-- Confirmation Modal -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <h2>Confirm Submission</h2>
                        </div>
                        <div class="bot">
                            <p>Are you sure you want to submit this feedback?</p>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <button class="cancel" onclick="closeModal()">Cancel</button>
                        <button class="yes" onclick="submitFeedback()">Yes, Submit</button>
                    </div>
                </div>
            </div>
        </div>

      <script>
          function showConfirmationModal() {
              var feedback = document.getElementById('feedbackText').value.trim();
              if (feedback === '') {
                  alert('Please enter your feedback before submitting.');
                  return;
              }
              document.getElementById('confirmationModal').style.display = 'block';
              document.getElementById('confirmationModal').classList.add('show');
          }

          function closeModal() {
              document.getElementById('confirmationModal').style.display = 'none';
              document.getElementById('confirmationModal').classList.remove('show');
          }

          function submitFeedback() {
              document.getElementById('feedbackForm').submit();
          }

      </script>
  </body>
  </html>
</x-therapist-layout>