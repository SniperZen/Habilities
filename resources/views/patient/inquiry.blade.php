<x-patient-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/inquiry.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>
    <div class="tcontainer">
        <main class="main-content">
            <h2>Seeking for support?</h2>
            <p>Please fill the form below so that we can assist you and connect you to our therapy services.</p>

            <div class="form-container">
                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="step inactive">
                        <div class="step-number">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step active">
                        <div class="step-number">2</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">3</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">4</div>
                        <div class="step-line"></div>
                    </div>
                </div>

                <form action="{{ route('patient.storeInquiryStep1') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="concerns">What are the concerns, issues, or difficulties?</label>
                        <div class="select-wrapper">
                            <select id="concerns" name="concerns">
                                <option value="">Select...</option>
                                <option value="Autism">Autism</option>
                                <option value="ADHD">Attention Deficit Hyperactivity Disorder (ADHD)</option>
                                <option value="down_syndrome">Down Syndrome</option>
                                <option value="GDD">Global Developmental Delay (GDD)</option>
                                <option value="Behavioral Problems">Behavioral Problems</option>
                                <option value="Learning Disabilities">Learning Disabilities</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="inf">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 7.5L7.5 10.3125M7.5 5.41534V5.39062M1.875 7.5C1.875 4.3934 4.3934 1.875 7.5 1.875C10.6066 1.875 13.125 4.3934 13.125 7.5C13.125 10.6066 10.6066 13.125 7.5 13.125C4.3934 13.125 1.875 10.6066 1.875 7.5Z" stroke="#A9ACB4" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <small>If your case is not listed, please select "others" and elaborate in the text box below.</small>
                        </div>
                    </div>

                    <div class="form-group textarea-wrapper">
                        <label for="elaboration">Please elaborate the case:</label>
                        <textarea id="elaboration" name="elaboration" rows="5" placeholder="Type here..."></textarea>
                        <small id="charCount">500 characters left</small>
                    </div>

                    <div class="button-group">
                    <a href="{{ route('patient.inquiry01') }}" class="button">Back</a>
                    <button type="submit" class="button">Next</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('elaboration');
            const charCountDisplay = document.getElementById('charCount');
            const maxChars = 500;

            textarea.addEventListener('input', function() {
                const currentLength = textarea.value.length;
                const charsLeft = maxChars - currentLength;
                charCountDisplay.textContent = `${charsLeft} characters left`;
            });
        });
    </script>
    </div>

    @if(session()->has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#28a745",
                    stopOnFocus: true,
                    close: true,
                }).showToast();
            });
        </script>
    @endif
</body>
</html>
    </x-patient-layout>