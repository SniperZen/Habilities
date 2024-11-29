<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention - Edit Profile</title>
    <link rel="stylesheet" href="{{ asset('css/patient/patientprofedit.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>
<body>

    <main class="main-content">
        <div class="tab-buttons">
            <button class="tab-button active">Personal Information</button>
            <a href="{{ route('patient.changespass') }}"><button class="tab-button">Change Password</button></a>
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
                    <h2>{{ $user->first_name }} {{ $user->middle_name ?? '' }} {{ $user->last_name }}</h2>
                    <p>Patient ID: P-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                    @if($user->account_type === 'child')
                        <span class="supervised-account">Supervised Account</span>
                    @endif

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

            <form id="profileForm" class="profile-form" method="POST" action="{{ route('edit-profile.update') }}" onsubmit="event.preventDefault(); openConfirmModal()">
    @csrf
    @method('patch')

    <!-- For debugging purposes -->
    @if(auth()->check())
        <div style="display: none;">
            Debug Info:
            User Type: {{ auth()->user()->usertype }}
            Account Type: {{ auth()->user()->account_type }}
        </div>
    @endif

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
        <label for="home_address">Address</label>
        <input type="text" id="home_address" name="home_address" value="{{ old('home_address', auth()->user()->home_address) }}">
    </div>

    {{-- Try different condition variations --}}
    @if(auth()->user()->account_type == 'child')
    <div class="form-group">
    <label for="guardian_role">Guardian Role</label>
    <select id="guardian_role" name="guardian_role" required onchange="toggleOtherGuardianRole()">
        <option value="">Select Relationship</option>
        <option value="Father" {{ $user->guardian_role == 'Father' ? 'selected' : '' }}>Father</option>
        <option value="Mother" {{ $user->guardian_role == 'Mother' ? 'selected' : '' }}>Mother</option>
        <option value="Grandfather" {{ $user->guardian_role == 'Grandfather' ? 'selected' : '' }}>Grandfather</option>
        <option value="Grandmother" {{ $user->guardian_role == 'Grandmother' ? 'selected' : '' }}>Grandmother</option>
        <option value="Aunt" {{ $user->guardian_role == 'Aunt' ? 'selected' : '' }}>Aunt</option>
        <option value="Uncle" {{ $user->guardian_role == 'Uncle' ? 'selected' : '' }}>Uncle</option>
        <option value="Sibling" {{ $user->guardian_role == 'Sibling' ? 'selected' : '' }}>Sibling</option>
        <option value="Legal Guardian" {{ $user->guardian_role == 'Legal Guardian' ? 'selected' : '' }}>Legal Guardian</option>
        <option value="Stepfather" {{ $user->guardian_role == 'Stepfather' ? 'selected' : '' }}>Stepfather</option>
        <option value="Stepmother" {{ $user->guardian_role == 'Stepmother' ? 'selected' : '' }}>Stepmother</option>
        <option value="Foster Parent" {{ $user->guardian_role == 'Foster Parent' ? 'selected' : '' }}>Foster Parent</option>
        <option value="other" {{ !in_array($user->guardian_role, ['Father', 'Mother', 'Grandfather', 'Grandmother', 'Aunt', 'Uncle', 'Sibling', 'Legal Guardian', 'Stepfather', 'Stepmother', 'Foster Parent']) ? 'selected' : '' }}>Other</option>
    </select>
</div>

<div class="form-group" id="otherGuardianRoleField" style="display: none;">
    <label for="other_guardian_role">Specify Guardian Role</label>
    <input type="text" id="other_guardian_role" name="other_guardian_role" class="form-control" 
           value="{{ !in_array($user->guardian_role, ['Father', 'Mother', 'Grandfather', 'Grandmother', 'Aunt', 'Uncle', 'Sibling', 'Legal Guardian', 'Stepfather', 'Stepmother', 'Foster Parent']) ? $user->guardian_role : '' }}"
           placeholder="Please specify the guardian role">
</div>


        <div class="form-group">
            <label for="guardian_name">Guardian Name</label>
            <input type="text" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', auth()->user()->guardian_name) }}" required>
        </div>
    @endif

    <div class="form-actions">
        <button type="button" class="cancel-btn" onclick="this.form.reset()">Clear</button>
        <button type="submit" class="save-btn">Save Changes</button>
    </div>
</form>

        </section>
    </main>
    <script>
function toggleOtherGuardianRole() {
    const guardianRoleSelect = document.getElementById('guardian_role');
    const otherGuardianRoleField = document.getElementById('otherGuardianRoleField');
    const otherGuardianRoleInput = document.getElementById('other_guardian_role');
    
    if (guardianRoleSelect.value === 'other') {
        otherGuardianRoleField.style.display = 'block';
        otherGuardianRoleInput.required = true;
    } else {
        otherGuardianRoleField.style.display = 'none';
        otherGuardianRoleInput.required = false;
    }
}

// Run on page load to handle initial state
document.addEventListener('DOMContentLoaded', function() {
    toggleOtherGuardianRole();
});
</script>
    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal" style="display: none;">
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
                <div class="modal-actions">
                    <button onclick="closeConfirmModal()" class="modal-btn cancel">Cancel</button>
                    <button onclick="submitForm()" class="modal-btn confirm">Yes</button>
                </div>
            </div>
        </div>
    </div>



    <script>
    const profileForm = document.getElementById('profileForm');
    const confirmModal = document.getElementById('confirmModal');
    const profilePreview = document.getElementById('profilePreview');
    const originalImageSrc = profilePreview.src;
    const cameraIcon = document.getElementById('cameraIcon');
    const cancelIcon = document.getElementById('cancelIcon');
    const submitImageButton = document.getElementById('submit-image');

    function openConfirmModal() {
    confirmModal.style.display = 'block';
        confirmModal.classList.add('show');
}

function closeConfirmModal() {
    confirmModal.classList.remove('show');
        confirmModal.style.display = 'none';
}

    function submitForm() {
        closeConfirmModal();
        // Store the toast message in localStorage
        localStorage.setItem('toastMessage', 'Profile updated successfully!');
        localStorage.setItem('toastBackgroundColor', '#28a745');
        profileForm.submit();
    }

    function previewImage(event) {
        if (event.target.files && event.target.files[0]) {
            profilePreview.src = URL.createObjectURL(event.target.files[0]);
            cameraIcon.style.visibility = 'hidden';
            cancelIcon.style.visibility = 'visible';
            submitImageButton.style.display = 'inline-block';
        }
    }

    function cancelImage(event) {
        event.stopPropagation();
        profilePreview.src = originalImageSrc;
        document.getElementById('profile_image').value = '';
        cancelIcon.style.visibility = 'hidden';
        cameraIcon.style.visibility = 'visible';
        submitImageButton.style.display = 'none';
    }

    // Function to show toast notification
    function showToast(message, backgroundColor) {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: 'right',
            backgroundColor: backgroundColor,
        }).showToast();
    }

    // Check for toast message on page load
    document.addEventListener('DOMContentLoaded', function() {
        const message = localStorage.getItem('toastMessage');
        const backgroundColor = localStorage.getItem('toastBackgroundColor');
        if (message) {
            showToast(message, backgroundColor);
            // Clear the message from local storage
            localStorage.removeItem('toastMessage');
            localStorage.removeItem('toastBackgroundColor');
        }
    });
</script>





</body>
</html>
</x-patient-layout>
