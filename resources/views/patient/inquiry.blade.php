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
                        <label for="concerns">What are the concerns, issues, or difficulties?<span style="color: red;">*</span></label>
                        <div class="select-wrapper">
                        <select id="concerns" name="concerns">
                            <option value="">Select...</option>
                            <option value="Autism" {{ old('concerns') == 'Autism' ? 'selected' : '' }}>Autism</option>
                            <option value="ADHD" {{ old('concerns') == 'ADHD' ? 'selected' : '' }}>Attention Deficit Hyperactivity Disorder (ADHD)</option>
                            <option value="Down Syndrome" {{ old('concerns') == 'Down Syndrome' ? 'selected' : '' }}>Down Syndrome</option>
                            <option value="GDD" {{ old('concerns') == 'GDD' ? 'selected' : '' }}>Global Developmental Delay (GDD)</option>
                            <option value="Behavioral Problems" {{ old('concerns') == 'Behavioral Problems' ? 'selected' : '' }}>Behavioral Problems</option>
                            <option value="Learning Disabilities" {{ old('concerns') == 'Learning Disabilities' ? 'selected' : '' }}>Learning Disabilities</option>
                            <option value="Others" {{ old('concerns') == 'Others' ? 'selected' : '' }}>Others</option>
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
                        <div class="pad">
                            <label for="elaboration">Please elaborate the case:<span style="color: red;">*</span></label>
                            <textarea id="elaboration" name="elaboration" rows="5" placeholder="Type here...">{{ old('elaboration') }}</textarea>
                        </div>
                        <small id="charCount">500 characters left</small>
                        @error('elaboration')
                            <div class="error-message" style="color: red; margin-top: 5px; font-weight:bold;">{{ $message }}</div>
                        @enderror
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
        const form = document.querySelector('form');
        const textarea = document.getElementById('elaboration');
        const charCountDisplay = document.getElementById('charCount');
        const maxChars = 500;

        // Set initial character count
        const initialLength = textarea.value.length;
        const initialCharsLeft = maxChars - initialLength;
        charCountDisplay.textContent = `${initialCharsLeft} characters left`;

        textarea.addEventListener('input', function() {
            const currentLength = textarea.value.length;
            const charsLeft = maxChars - currentLength;
            charCountDisplay.textContent = `${charsLeft} characters left`;
            
            if (currentLength > maxChars) {
                charCountDisplay.style.color = 'red';
            } else {
                charCountDisplay.style.color = '';
            }
        });

        form.addEventListener('submit', function(e) {
            const currentLength = textarea.value.length;
            if (currentLength > maxChars) {
                e.preventDefault();
                Toastify({
                    text: "The elaboration field must not exceed 500 characters",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true,
                    close: true,
                }).showToast();
            }
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