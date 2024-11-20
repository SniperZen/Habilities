<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile - Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist/edit.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<style>
    .form-check-inline-container {
    display: flex;
    flex-wrap: wrap;
}

.form-check-inline {
    margin-right: 10px; /* Adjust spacing between checkboxes */
}

</style>
<body>
        <main class="main-content">
        <div class="tab-buttons">
            <button class="tab-button active">Personal Information</button>
            <a href="{{route('therapist.changespass')}}"><button class="tab-button" >Change Password</button></a>
        </div>
            <section class="profile-section">
            <div class="profile-card">
                <div class="profile-image-container" onclick="document.getElementById('profile_image').click()">
                    <img id="profilePreview" src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'path/to/default/image.jpg' }}" alt="Profile Picture">
                    <div class="camera-icon" id="cameraIcon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div class="cancel-icon" id="cancelIcon" onclick="cancelImage(event)">
                        <i class="fas fa-times"></i>
                    </div>
                </div>

                <div class="profile-info">
                <p class="patient-name">{{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }}</p>
                        @if($user->usertype === 'therapist')
                        <p>{{ $user->specialization }}</p>
                    @elseif($user->usertype === 'admin')
                        <p>Administrator</p>
                    @else
                        <p>User</p>
                    @endif
                    <p>Therapist ID: T-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>

                    <div class="profile-upload">
                    <form action="{{ route('profile.upload-image') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="previewImage(event)">
                        <button type="submit" id="submit-image" style="display: none;">Upload Image</button>
                    </form>
                </div>
                </div>
    
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="profileForm" class="profile-form" method="POST" action="{{ route('edit-profile.update') }}">
                @csrf
                @method('patch')
                
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', auth()->user()->middle_name) }}">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Birthday</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="male" {{ old('gender', auth()->user()->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', auth()->user()->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', auth()->user()->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', auth()->user()->contact_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <input type="text" id="specialization" name="specialization" value="{{ old('specialization', auth()->user()->specialization) }}">
                    </div>
                    <div class="form-group">
                        <label for="home_address">Address</label>
                        <input type="text" id="home_address" name="home_address" value="{{ old('home_address', auth()->user()->home_address) }}">
                    </div>
                    <div class="form-group">
                        <label for="teletherapist_link">Tele-therapist Link</label>
                        <input type="url" id="teletherapist_link" name="teletherapist_link" value="{{ old('teletherapist_link', auth()->user()->teletherapist_link) }}">
                    </div>
                    <div class="form-group">
                        <label for="availability">Availability</label><br>
                        <div class="form-check-inline-container">
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $userAvailability = old('availability', auth()->user()->availability ?? '');

                                // Convert to array for checkbox logic
                                if (is_string($userAvailability)) {
                                    $userAvailability = explode(',', $userAvailability);
                                }
                            @endphp
                            @foreach($days as $day)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="availability_{{ $day }}" name="availability[]" value="{{ $day }}"
                                        {{ in_array($day, $userAvailability) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="availability_{{ $day }}">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn" onclick="this.form.reset()">Clear</button>
                        <button type="button" class="save-btn" onclick="openConfirmModal()">Save Changes</button>
                    </div>
                </form>

            </section>
        </main>
    </div>

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
                    <button onclick="submitForm()" class="yes-btn">Yes</button>
                    <button onclick="closeConfirmModal()" class="no-btn">No</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
        <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner" style="margin-bottom: 30px;">
                    <h2>Your changes have been saved successfully!</h2>
                </div>
                <button onclick="closeSuccessModal()" class="close-btn">OK</button>
            </div>
        </div>
    </div>

    <script>
    const confirmModal = document.getElementById('confirmModal');

    function openConfirmModal() {
        confirmModal.style.display = 'flex';
    }

    function closeConfirmModal() {
        confirmModal.style.display = 'none';
    }

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

    function submitForm() {
        closeConfirmModal();
        document.getElementById('profileForm').submit();
        // Store a flag in sessionStorage
        sessionStorage.setItem('showToast', 'true');
    }



    document.getElementById('profileForm').addEventListener('submit', function (e) {
        e.preventDefault();
        submitForm();
    });
    </script>


    <script>
        const profilePreview = document.getElementById('profilePreview');
        const originalImageSrc = profilePreview.src; // Save the original image source
        const cameraIcon = document.getElementById('cameraIcon');
        const cancelIcon = document.getElementById('cancelIcon');
        const submitImageButton = document.getElementById('submit-image');

        // Preview the selected image
        function previewImage(event) {
            if (event.target.files && event.target.files[0]) {
                // Show the selected image as preview
                profilePreview.src = URL.createObjectURL(event.target.files[0]);

                // Toggle icons and show submit button
                cameraIcon.style.visibility = 'hidden';
                cancelIcon.style.visibility = 'visible';
                submitImageButton.style.display = 'inline-block';
            }
        }

        // Cancel the image selection and revert to the original image
        function cancelImage(event) {
            event.stopPropagation(); // Prevent triggering the file input click
            profilePreview.src = originalImageSrc; // Reset to the original image
            document.getElementById('profile_image').value = ''; // Clear the file input
            cancelIcon.style.visibility = 'hidden'; // Hide the cancel icon
            cameraIcon.style.visibility = 'visible'; // Show the camera icon
            submitImageButton.style.display = 'none'; // Hide the submit button
        }
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