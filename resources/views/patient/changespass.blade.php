<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention - Edit Profile</title>
    <link rel="stylesheet" href="{{ asset('css/patient/patientprofedit.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
    </style>
</head>
<body>
    <main class="main-content">
        <div class="tab-buttons">
            <a href="{{route('patient.edit-profile')}}"><button class="tab-button">Personal Information</button></a>
            <button class="tab-button active">Change Password</button>
        </div>
        
        <section id="change-password" class="profile-section">
            <form id="passwordForm" class="password-change-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                
                <h3>Change Password</h3>
                
                @if (session('status') === 'password-updated')
                    <script>
                        Toastify({
                            text: "Password updated successfully!",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "green",
                                color: "white"
                            }
                        }).showToast();
                    </script>
                @endif

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password', 'updatePassword')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password', 'updatePassword')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="restriction">
                    <p>
                        <span id="uppercase-check" class="check-icon">✕</span> Contain at least one uppercase letter (A-Z)<br>
                        <span id="lowercase-check" class="check-icon">✕</span> Contain at least one lowercase letter (a-z)<br>
                        <span id="number-check" class="check-icon">✕</span> Contain at least one number (0-9)<br>
                        <span id="special-char-check" class="check-icon">✕</span> Contain at least one special character (~`! @#$%^&*()-_+={}[]|\;:"<>,./?)<br>
                        <span id="min-length-check" class="check-icon">✕</span> Be at least 8 characters long
                    </p>
                </div>

                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="this.form.reset()">Cancel</button>
                    <button type="button" class="save-btn" id="submitButton" disabled>Change Password</button>
                </div>
            </form>
        </section>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <h2>Save Changes</h2>
                    </div>
                    <div class="bot">
                        <p>Are you sure you want to save changes?</p>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button id="noButton" class="no-btn">No</button>
                    <button id="yesButton" class="yes-btn">Yes</button>
                </div>
            </div>    
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const lowercaseCheck = document.getElementById('lowercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCharCheck = document.getElementById('special-char-check');
            const minLengthCheck = document.getElementById('min-length-check');
            const submitButton = document.getElementById('submitButton');
            const passwordForm = document.getElementById('passwordForm');
            const confirmModal = document.getElementById('confirmModal');
            const noButton = document.getElementById('noButton');
            const yesButton = document.getElementById('yesButton');

            function checkPasswordRequirements() {
                const password = passwordInput.value;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecialChar = /[~`!@#$%^&*()_\-+={}[\]|;:"<>,./?]/.test(password);
                const isAtLeast8Chars = password.length >= 8;

                uppercaseCheck.textContent = hasUppercase ? '✓' : '✕';
                lowercaseCheck.textContent = hasLowercase ? '✓' : '✕';
                numberCheck.textContent = hasNumber ? '✓' : '✕';
                specialCharCheck.textContent = hasSpecialChar ? '✓' : '✕';
                minLengthCheck.textContent = isAtLeast8Chars ? '✓' : '✕';

                const isValid = hasUppercase && hasLowercase && hasNumber && hasSpecialChar && isAtLeast8Chars;
                submitButton.disabled = !isValid;
            }

            passwordInput.addEventListener('input', checkPasswordRequirements);

            // Show confirmation modal on submit button click
            submitButton.addEventListener('click', function() {
                confirmModal.style.display = 'block';
            });

            // Handle no button click
            noButton.addEventListener('click', function() {
                confirmModal.style.display = 'none';
            });

            // Handle yes button click
            yesButton.addEventListener('click', function() {
                confirmModal.style.display = 'none';
                Toastify({
                    text: "Changing password...",
                    duration: 2000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "blue",
                        color: "white"
                    }
                }).showToast();
                
                // Submit the form after a short delay
                passwordForm.submit();
            });
        });
    </script>
</body>
</html>
</x-patient-layout>
