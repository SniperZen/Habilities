<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/inquiry3.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>
<body>

<div class="tcontainer">
    <main class="main-content">
        <h2>One step closer</h2>
        <p class="p">Please make sure that answers and documents are correct. We ensure that all data are treated with confidentiality.</p>

        <div class="form-container">
            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="step inactive">
                    <div class="step-number">1</div>
                    <div class="step-line"></div>
                </div>
                <div class="step inactive">
                    <div class="step-number">2</div>
                    <div class="step-line"></div>
                </div>
                <div class="step inactive">
                    <div class="step-number">3</div>
                    <div class="step-line"></div>
                </div>
                <div class="step aactive">
                        <div class="step-number">4</div>
                        <div class="step-line"></div>
                </div>
            </div>

            <div class="inquiry-details">
                <h3>Inquiry Details</h3>
                <p><strong>Concerns:</strong> {{ session('inquiry.concerns', 'N/A') }}</p>
                <p><strong>Elaboration:</strong> {{ session('inquiry.elaboration', 'N/A') }}</p>
                <div class="attachments">
                <strong><p>Attached Documents:</p></strong>
                @if(session('inquiry.identification_card'))
                    <div class="attachment">
                        <a href="{{ Storage::url(session('inquiry.identification_card')) }}" class="attachment-link" target = "_blank">
                            <svg width="50" height="47" viewBox="0 0 50 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M38 45.5H23.7977C17.9987 45.5 13.2977 40.799 13.2977 35V12C13.2977 6.20101 17.9987 1.5 23.7977 1.5H33.3795L48.5 16.2091V35C48.5 40.799 43.799 45.5 38 45.5Z" fill="#EEF1F7" stroke="#CBD0DC" stroke-width="3"/>
                                <rect y="20.7676" width="33.7079" height="19.1279" rx="7" fill="#D82042"/>
                                <path d="M5.58667 34.9766V25.835H8.93631C9.66742 25.835 10.2731 25.9644 10.7534 26.2233C11.2336 26.4822 11.5931 26.8363 11.8317 27.2857C12.0703 27.732 12.1896 28.2349 12.1896 28.7944C12.1896 29.3568 12.0688 29.8627 11.8271 30.312C11.5885 30.7584 11.2275 31.1125 10.7442 31.3744C10.2639 31.6333 9.65977 31.7627 8.93172 31.7627H6.62827V30.5933H8.80324C9.26516 30.5933 9.63989 30.5159 9.92744 30.3611C10.215 30.2034 10.4261 29.9892 10.5607 29.7184C10.6953 29.4476 10.7626 29.1396 10.7626 28.7944C10.7626 28.4492 10.6953 28.1427 10.5607 27.8749C10.4261 27.607 10.2135 27.3973 9.92285 27.2455C9.6353 27.0937 9.25598 27.0178 8.78489 27.0178H7.00453V34.9766H5.58667Z" fill="white"/>
                                <path d="M16.894 34.9766H13.8518V25.835H16.9904C17.9111 25.835 18.7019 26.018 19.3626 26.384C20.0234 26.747 20.5297 27.2693 20.8814 27.9508C21.2363 28.6292 21.4137 29.4431 21.4137 30.3924C21.4137 31.3446 21.2348 32.163 20.8769 32.8474C20.522 33.5319 20.0081 34.0586 19.3351 34.4276C18.6621 34.7936 17.8484 34.9766 16.894 34.9766ZM15.2697 33.7714H16.816C17.5318 33.7714 18.1268 33.6405 18.6009 33.3786C19.0751 33.1138 19.4299 32.7314 19.6655 32.2314C19.901 31.7285 20.0188 31.1155 20.0188 30.3924C20.0188 29.6752 19.901 29.0667 19.6655 28.5667C19.433 28.0668 19.0858 27.6874 18.6239 27.4285C18.162 27.1696 17.5884 27.0402 16.9032 27.0402H15.2697V33.7714Z" fill="white"/>
                                <path d="M23.1769 34.9766V25.835H29.0043V27.0223H24.5947V29.8077H28.5868V30.9905H24.5947V34.9766H23.1769Z" fill="white"/>
                                </svg>
                                Identification Card
                        </a>
                    </div>
                @endif
                @if(session('inquiry.birth_certificate'))
                    <div class="attachment">
                        <a href="{{ Storage::url(session('inquiry.birth_certificate')) }}" class="attachment-link" target = "_blank">
                            <svg width="50" height="47" viewBox="0 0 50 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M38 45.5H23.7977C17.9987 45.5 13.2977 40.799 13.2977 35V12C13.2977 6.20101 17.9987 1.5 23.7977 1.5H33.3795L48.5 16.2091V35C48.5 40.799 43.799 45.5 38 45.5Z" fill="#EEF1F7" stroke="#CBD0DC" stroke-width="3"/>
                                <rect y="20.7676" width="33.7079" height="19.1279" rx="7" fill="#D82042"/>
                                <path d="M5.58667 34.9766V25.835H8.93631C9.66742 25.835 10.2731 25.9644 10.7534 26.2233C11.2336 26.4822 11.5931 26.8363 11.8317 27.2857C12.0703 27.732 12.1896 28.2349 12.1896 28.7944C12.1896 29.3568 12.0688 29.8627 11.8271 30.312C11.5885 30.7584 11.2275 31.1125 10.7442 31.3744C10.2639 31.6333 9.65977 31.7627 8.93172 31.7627H6.62827V30.5933H8.80324C9.26516 30.5933 9.63989 30.5159 9.92744 30.3611C10.215 30.2034 10.4261 29.9892 10.5607 29.7184C10.6953 29.4476 10.7626 29.1396 10.7626 28.7944C10.7626 28.4492 10.6953 28.1427 10.5607 27.8749C10.4261 27.607 10.2135 27.3973 9.92285 27.2455C9.6353 27.0937 9.25598 27.0178 8.78489 27.0178H7.00453V34.9766H5.58667Z" fill="white"/>
                                <path d="M16.894 34.9766H13.8518V25.835H16.9904C17.9111 25.835 18.7019 26.018 19.3626 26.384C20.0234 26.747 20.5297 27.2693 20.8814 27.9508C21.2363 28.6292 21.4137 29.4431 21.4137 30.3924C21.4137 31.3446 21.2348 32.163 20.8769 32.8474C20.522 33.5319 20.0081 34.0586 19.3351 34.4276C18.6621 34.7936 17.8484 34.9766 16.894 34.9766ZM15.2697 33.7714H16.816C17.5318 33.7714 18.1268 33.6405 18.6009 33.3786C19.0751 33.1138 19.4299 32.7314 19.6655 32.2314C19.901 31.7285 20.0188 31.1155 20.0188 30.3924C20.0188 29.6752 19.901 29.0667 19.6655 28.5667C19.433 28.0668 19.0858 27.6874 18.6239 27.4285C18.162 27.1696 17.5884 27.0402 16.9032 27.0402H15.2697V33.7714Z" fill="white"/>
                                <path d="M23.1769 34.9766V25.835H29.0043V27.0223H24.5947V29.8077H28.5868V30.9905H24.5947V34.9766H23.1769Z" fill="white"/>
                            </svg>Birth Certificate
                        </a>
                    </div>
                @endif
                @if(session('inquiry.diagnosis_reports'))
                    <div class="attachment">
                        <a href="{{ Storage::url(session('inquiry.diagnosis_reports')) }}" class="attachment-link" target = "_blank">
                            <svg width="50" height="47" viewBox="0 0 50 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M38 45.5H23.7977C17.9987 45.5 13.2977 40.799 13.2977 35V12C13.2977 6.20101 17.9987 1.5 23.7977 1.5H33.3795L48.5 16.2091V35C48.5 40.799 43.799 45.5 38 45.5Z" fill="#EEF1F7" stroke="#CBD0DC" stroke-width="3"/>
                                <rect y="20.7676" width="33.7079" height="19.1279" rx="7" fill="#D82042"/>
                                <path d="M5.58667 34.9766V25.835H8.93631C9.66742 25.835 10.2731 25.9644 10.7534 26.2233C11.2336 26.4822 11.5931 26.8363 11.8317 27.2857C12.0703 27.732 12.1896 28.2349 12.1896 28.7944C12.1896 29.3568 12.0688 29.8627 11.8271 30.312C11.5885 30.7584 11.2275 31.1125 10.7442 31.3744C10.2639 31.6333 9.65977 31.7627 8.93172 31.7627H6.62827V30.5933H8.80324C9.26516 30.5933 9.63989 30.5159 9.92744 30.3611C10.215 30.2034 10.4261 29.9892 10.5607 29.7184C10.6953 29.4476 10.7626 29.1396 10.7626 28.7944C10.7626 28.4492 10.6953 28.1427 10.5607 27.8749C10.4261 27.607 10.2135 27.3973 9.92285 27.2455C9.6353 27.0937 9.25598 27.0178 8.78489 27.0178H7.00453V34.9766H5.58667Z" fill="white"/>
                                <path d="M16.894 34.9766H13.8518V25.835H16.9904C17.9111 25.835 18.7019 26.018 19.3626 26.384C20.0234 26.747 20.5297 27.2693 20.8814 27.9508C21.2363 28.6292 21.4137 29.4431 21.4137 30.3924C21.4137 31.3446 21.2348 32.163 20.8769 32.8474C20.522 33.5319 20.0081 34.0586 19.3351 34.4276C18.6621 34.7936 17.8484 34.9766 16.894 34.9766ZM15.2697 33.7714H16.816C17.5318 33.7714 18.1268 33.6405 18.6009 33.3786C19.0751 33.1138 19.4299 32.7314 19.6655 32.2314C19.901 31.7285 20.0188 31.1155 20.0188 30.3924C20.0188 29.6752 19.901 29.0667 19.6655 28.5667C19.433 28.0668 19.0858 27.6874 18.6239 27.4285C18.162 27.1696 17.5884 27.0402 16.9032 27.0402H15.2697V33.7714Z" fill="white"/>
                                <path d="M23.1769 34.9766V25.835H29.0043V27.0223H24.5947V29.8077H28.5868V30.9905H24.5947V34.9766H23.1769Z" fill="white"/>
                            </svg>Diagnosis Reports
                        </a>
                    </div>
                @endif
            </div>
            </div>

            <!-- Form to Confirm Inquiry -->
            <form action="{{ route('confirm.inquiry') }}" method="POST" id="confirmForm">
                @csrf
                <input type="hidden" name="concerns" value="{{ session('inquiry.concerns', 'N/A') }}">
                <input type="hidden" name="elaboration" value="{{ session('inquiry.elaboration', 'N/A') }}">
                <input type="hidden" name="identification_card" value="{{ session('inquiry.identification_card') }}">
                <input type="hidden" name="birth_certificate" value="{{ session('inquiry.birth_certificate') }}">
                <input type="hidden" name="diagnosis_reports" value="{{ session('inquiry.diagnosis_reports') }}">
                
                <div class="button-group">
                    <a href="{{ route('patient.inquiry2') }}" class="button">Back</a>
                    <button type="button" id="confirmButton" class="confirm-button">Confirm Inquiry</button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="modalOverlay" class="modal-overlay" style="display: none;">
    <div class="modals">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Confirm Submission</h2>
                </div>
                <div class="bot">
                    <p>Are you sure you want to confirm this inquiry? Once submitted, changes cannot be made.</p>
                </div>
            </div>
            <div class="modal-buttons">
                <button id="cancelButton" class="modal-cancel-button">Cancel</button>
                <button id="submitButton" class="modal-confirm-button">Yes, Confirm</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmButton = document.getElementById('confirmButton');
        const modalOverlay = document.getElementById('modalOverlay');
        const cancelButton = document.getElementById('cancelButton');
        const submitButton = document.getElementById('submitButton');
        const confirmForm = document.getElementById('confirmForm');

        // Show modal when confirm button is clicked
        confirmButton.addEventListener('click', function () {
            console.log("Confirm button clicked");
            modalOverlay.style.display = 'block';
        });

        // Hide modal when cancel button is clicked
        cancelButton.addEventListener('click', function () {
            console.log("Cancel button clicked");
            modalOverlay.style.display = 'none';
        });

        // Submit the form when confirm button in the modal is clicked
        submitButton.addEventListener('click', function () {
            console.log("Submitting form");
            confirmForm.submit();
        });
    });
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            (session('toast_message'))
                Toastify({
                    text: "{{ session('toast_message') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: 'right',
                    backgroundColor: "{{ session('toast_type') === 'success' ? '#28a745' : '#dc3545' }}",
                }).showToast();
        
        });
    </script>

</body>
</html>
</x-patient-layout>
