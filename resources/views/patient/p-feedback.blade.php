<x-patient-layout>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="{{ asset('css/patient/p-feedback.css') }}">
      <title>Feedback</title>
      <style>
          .modal {
              display: none;
              position: fixed;
              z-index: 1;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              overflow: auto;
              background-color: rgba(0,0,0,0.4);
          }
          .modal-content {
              background-color: #fefefe;
              margin: 15% auto;
              padding: 20px;
              border: 1px solid #888;
              width: 80%;
              max-width: 500px;
              border-radius: 5px;
              text-align: center;
          }
          .modal-buttons {
              margin-top: 20px;
          }
          .modal-buttons button {
              margin: 0 10px;
              padding: 10px 20px;
              border: none;
              border-radius: 5px;
              cursor: pointer;
          }
          .modal-buttons button:first-child {
              background-color: #4CAF50;
              color: white;
          }
          .modal-buttons button:last-child {
              background-color: #f44336;
              color: white;
          }
      </style>
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

              <form id="feedbackForm" action="{{ route('patient.feedback.store') }}" method="POST">
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
              <h2>Confirm Submission</h2>
              <p>Are you sure you want to submit this feedback?</p>
              <div class="modal-buttons">
                  <button onclick="submitFeedback()">Yes, Submit</button>
                  <button onclick="closeModal()">Cancel</button>
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
          }

          function closeModal() {
              document.getElementById('confirmationModal').style.display = 'none';
          }

          function submitFeedback() {
              document.getElementById('feedbackForm').submit();
          }

          // Close the modal if the user clicks outside of it
          window.onclick = function(event) {
              var modal = document.getElementById('confirmationModal');
              if (event.target == modal) {
                  closeModal();
              }
          }
      </script>
  </body>
  </html>
</x-patient-layout>