<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
        [x-cloak] { display: none !important; }
        
        .modal-deactivate {
            z-index: 1200;
        }
        .modal-usertype {
            z-index: 1100;
        }
        .modal-success {
            z-index: 1150;
        }
        .modal-activate {
            z-index: 1200;
        }
        .modal-save {
            z-index: 1200;
        }
        
        .showDetails {
            position: relative;
            z-index: 900;
        }

        .modals {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        @keyframes popUpEffect {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            60% {
                transform: scale(1.1);
                opacity: 1;
            }
            80% {
                transform: scale(0.95);
            }
            100% {
                transform: scale(1);
            }
        }

        .modal-contents {
            transform: scale(0.5);
            animation-fill-mode: forwards;
            animation-timing-function: ease-out; 
            animation-duration: 0.2s; 
        }
        .modals.show {
            display: block;
            opacity: 1;
        }

        .modals.show .modal-contents {
            animation-name: popUpEffect; 
        }

        .form-group select:not(:placeholder-shown) + label,
.form-group select:focus + label {
    transform: translateY(-20px);
}
        
        /* .modal {
    display: none;
    position: fixed;
    z-index: 1200;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
} */

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Therapists</title>
    <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<body>
<div x-cloak x-data="{ 
    showDetails: false, 
    therapist: null, 
    editMode: false, 
    showDeactivateModal: false,
    showActivateModal: false,
    showUserTypeModal: false,
    showSuccessModal: false,
    showSaveChangesModal: false,
    tempUserType: '',
    selectedTherapistId: null,
    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },
    expandTherapist(therapistData) {
        this.therapist = therapistData;
        this.showDetails = true;
    }
}">

    <div class="dashboard-container">
        <div class="int">
    <div x-show="!showDetails">
    <h2>Manage Users &gt; Therapist</h2>
    <div class="search_area">
        <button class="addtherap" type="button" onclick="document.getElementById('addTherapistModal').style.display='block'; document.getElementById('addTherapistModal').classList.add('show');"><i class="fas fa-plus"></i>Add Therapist</button>
            <input class="search" 
                   type="text" 
                   id="searchInput"
                   placeholder="Search for Therapist..." 
                   autocomplete="off">
            <button class="but"><img src="{{asset('images/icons/search.png')}}" alt=""></button>
            <button class="but refresh" onclick="window.location.reload()">
                <img src="{{asset('images/icons/refresh.png')}}" alt="">
            </button>
        </div>

    <table>
        <thead>
            <tr>
                <th>Therapist Name</th>
                <th>Therapist ID</th>
                <th>Specialization</th>
                <!--<th>User Level</th>-->
                <th>Account Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody id="therapistTableBody">
            @foreach($therapists as $therapist)
                <tr>
                    <td>{{ $therapist->name }}</td>
                    <td>{{ 'T-000' . $therapist->id }}</td>
                    <td>{{ $therapist->specialization ?? 'Not Specified' }}</td>
                    <!--<td>
                    <div class="select-wrapper">
                        <select class="user-type-select" 
                                @change="
                                    showUserTypeModal = true; 
                                    tempUserType = $event.target.value; 
                                    selectedTherapistId = {{ $therapist->id }};"
                                data-original-value="{{ $therapist->usertype }}"
                                data-therapist-id="{{ $therapist->id }}">
                            <option value="user" {{ $therapist->usertype == 'user' ? 'selected' : '' }}>Patient</option>
                            <option value="therapist" {{ $therapist->usertype == 'therapist' ? 'selected' : '' }}>Therapist</option>
                        </select>
                    </div>
                    </td>-->
                    <td>
                        <span class="status {{ $therapist->account_status == 'active' ? 'active' : 'deactivated' }}">
                            {{ ucfirst($therapist->account_status) }}
                        </span>
                    </td>
                    <td>
                        <button class="expand" @click="showDetails = true; therapist = JSON.parse('{{ json_encode($therapist) }}'); editMode = false;">
                            Expand
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


    <!-- Therapist Details Section -->
    <div x-show="showDetails" class="showDetails">
        <div class="profile-card">
            <img :src="therapist && therapist.profile_image ? '{{ Storage::url('') }}' + therapist.profile_image : '{{asset('images/others/default-prof.png')}}'" :alt="therapist ? therapist.name : 'Default Profile'">
            <div class="info">
            <h3 x-text="therapist ? therapist.name : ''"></h3>
            <p x-text="'Therapist ID: T-000' + (therapist ? therapist.id : '')"></p>
            <p x-text="'Specialization: ' + (therapist && therapist.specialization ? therapist.specialization : 'Not Specified')"></p>
            <p x-text="'On Duty Every: ' + (therapist && therapist.availability ? therapist.availability.replace(/,/g, ', ') : 'Availability not specified')"></p>
            <p x-text="'Email: ' + therapist.email"></p>

            </div>
            <button class="button" 
                    :class="therapist && therapist.account_status === 'active' ? 'button-danger' : 'button-primary'" 
                    @click="therapist && therapist.account_status === 'active' ? showDeactivateModal = true : showActivateModal = true">
                <span x-text="therapist && therapist.account_status === 'active' ? 'Deactivate Account' : 'Activate Account'"></span>
            </button>           
            
            <button class="back" @click="showDetails = false">Back to List</button>
        </div>

        <div class="account-info">
            <div class="inf">
            <h2>Account Information</h2>
            <form 
                x-ref="therapistForm"
                method="POST" 
                x-bind:action="'/admin/updateUser/' + (therapist ? therapist.id : '')"
                @submit.prevent="showSaveChangesModal = true">            
                @csrf
                @method('PATCH')
                <input type="hidden" name="user_id" x-bind:value="therapist ? therapist.id : ''">

                <div class="form-group">
                    <input type="text" id="first_name" name="first_name" x-model="therapist.first_name" required>
                    <label for="first_name">First Name</label>
                </div>
                <div class="form-group">
                    <input type="text" id="middle_name" name="middle_name" x-model="therapist.middle_name">
                    <label for="middle_name">Middle Name</label>
                </div>
                <div class="form-group">
                    <input type="text" id="last_name" name="last_name" x-model="therapist.last_name" required>
                    <label for="last_name">Last Name</label>
                </div>
                <div class="form-group">
                    <input type="date" 
                        id="date_of_birth" 
                        name="date_of_birth" 
                        x-bind:value="therapist ? formatDate(therapist.date_of_birth) : ''">
                    <label for="date_of_birth">Birthday</label>
                </div>

                <div class="form-group gender">
                    <select id="gender" name="gender" x-model="therapist.gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <label for="gender">Gender</label>
                </div>
                <div class="form-group">
                    <input type="text" id="contact_number" name="contact_number" x-model="therapist.contact_number">
                    <label for="contact_number">Contact Number</label>
                </div>
                <div class="form-group">
                    <input type="text" id="specialization" name="specialization" x-model="therapist.specialization">
                    <label for="specialization">Specialization</label>
                </div>
                <div class="form-group">
                    <input type="text" id="home_address" name="home_address" x-model="therapist.home_address">
                    <label for="home_address">Address</label>
                </div>
                <!--<div class="form-group">
                    <label for="availability">Availability</label><br>
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    @endphp
                    <template x-for="day in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" :id="'availability_' + day" name="availability[]" :value="day"
                                x-bind:checked="therapist.availability.includes(day)">
                            <label class="form-check-label" :for="'availability_' + day" x-text="day"></label>
                        </div>
                    </template>
                </div>-->

                <div class="form-actions">
                    <button type="button" class="cancel-btn" @click="editMode = false; showDetails = false;">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<script>
    function deactivateAccount(userId) {
        fetch(`/admin/users/${userId}/deactivate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload(); // Reload the page upon successful deactivation
            } else {
                alert('Failed to deactivate account.');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }

    function activateAccount(userId) {
        fetch(`/admin/users/${userId}/activate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload(); // Reload the page upon successful activation
            } else {
                alert('Failed to activate account.');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }

    function updateUserType(userId, newUserType) {
        fetch(`/admin/users/${userId}/update-usertype`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ usertype: newUserType })
        }).then(response => {
            if (response.ok) {
                window.location.reload(); // Reload the page upon successful update
            } else {
                alert('Failed to update user type.');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('therapistTableBody');
    const rows = tableBody.getElementsByTagName('tr');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const searchQuery = this.value.toLowerCase();

            Array.from(rows).forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const id = row.cells[1].textContent.toLowerCase();
                const specialization = row.cells[2].textContent.toLowerCase();

                const matches = name.includes(searchQuery) || 
                              id.includes(searchQuery) || 
                              specialization.includes(searchQuery);
                
                row.style.display = matches ? '' : 'none';
            });
        }, 300); // 300ms delay
    });
});
</script>
<!-- Deactivate Account Modal -->
<div x-show="showDeactivateModal"  x-cloak class="modal modal-deactivate">
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="red" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                        <line x1="12" y1="7" x2="12" y2="13" stroke-width="2" /> <!-- Exclamation line -->
                        <circle cx="12" cy="16" r="1" fill="currentColor" /> <!-- Exclamation dot -->
                    </svg>
                    <h2>Deactivate this Account?</h2>
                </div>
                <div class="bot">
                    <p>You’re about to deactivate this account, which will temporarily disable access and hide the profile from others. If you’re certain about this decision, please confirm to proceed. The account can be reactivated at any time by logging back in.</p>
                </div>
            </div>    
            <div class="modal-actions">
                <button class="cancel-btn" @click="showDeactivateModal = false">Cancel</button>
                <button @click="deactivateAccount(therapist.id); showDeactivateModal = false" class="save-btn">Confirm</button>            
            </div>               
        </div>
    </div>
</div>

<!-- Activate Account Modal -->
<div x-show="showActivateModal" x-cloak class="modal modal-activate">
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="green" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                        <path d="M9 12l2 2l4-4" stroke="green" stroke-width="2" fill="none" />
                    </svg>
                <h2>Activate this Account?</h2>
                </div>
                <div class="bot">
                <p>You're about to reactivate this account, which will restore access and make the profile visible to others. If you're certain about this decision, please confirm to proceed.</p>
                </div>
            </div>
            <div class="modal-actions">
                 <button class="cancel-btn" @click="showActivateModal = false">Cancel</button>
                <button @click="activateAccount(therapist.id); showActivateModal = false" class="save-btn">Confirm</button>            
            </div>
        </div>
    </div>
</div>


<!-- User Type Change Confirmation Modal -->
<div x-show="showUserTypeModal" class="modal modal-usertype" x-cloak>
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <img src="{{asset('images/admin/change.png')}}" alt="">
                    <h2>Change User Type</h2>
                </div>
                <div class="bot">
                    <p>Are you sure you want to change this user's type?</p>
                </div>
            </div>
                <div class="modal-actions">
                    <button class="cancel-btn" @click="
                        showUserTypeModal = false;
                        $event.target.closest('.modal').querySelector('select').value = $event.target.closest('.modal').querySelector('select').dataset.originalValue;
                    ">Cancel</button>
                    <button class="save-btn" @click="
                        fetch(`/admin/users/${selectedTherapistId}/update-usertype`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ usertype: tempUserType })
                        })
                        .then(response => {
                            if (response.ok) {
                                showUserTypeModal = false;
                                showSuccessModal = true;
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                throw new Error('Failed to update user type');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while updating user type.');
                        })
                    ">Confirm</button>
            </div>   
        </div>
    </div>
</div>

<!-- Success Modal -->
<div x-show="showSuccessModal" class="modal modal-success" x-cloak>
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Success!</h2>
                </div>
                <div class="bot">
                    <p>User type has been updated successfully.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button class="save-btn" @click="showSuccessModal = false">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Save Changes Modal -->
<div x-show="showSaveChangesModal" x-cloak class="modal modal-deactivate">
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
                        <p>Are you sure you want to save these changes?</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="cancel-btn" @click="showSaveChangesModal = false">Cancel</button>
                    <button @click="$refs.therapistForm.submit(); showSaveChangesModal = false" class="save-btn">Confirm</button>            
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- Add Therapist Modal -->
<div id="addTherapistModal" class="modal modals" style="display: none;">
    <div class="modal-contents">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Add New Therapist</h2>
                </div>
                <form id="addTherapistForm" action="{{ route('admin.therapists.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="first_name" name="first_name" placeholder=" " required>
                        <label for="first_name">First Name<span style="color: red;">*</span></label>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" id="middle_name" name="middle_name" placeholder=" ">
                        <label for="middle_name">Middle Name</label>
                    </div>

                    <div class="form-group">
                        <input type="text" id="last_name" name="last_name" placeholder=" " required>
                        <label for="last_name">Last Name<span style="color: red;">*</span></label>
                    </div>

                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email">Email<span style="color: red;">*</span></label>
                    </div>

                    <!-- Added Date of Birth field -->
                    <div class="form-group">
                        <input type="date" id="date_of_birth" name="date_of_birth" placeholder=" " required>
                        <label for="date_of_birth">Date of Birth<span style="color: red;">*</span></label>
                    </div>

                    <!-- Modified Gender field -->
                    <div class="form-group">
                        <select id="gender" name="gender" placeholder=" " required>
                            <option value="" disabled selected hidden></option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <label for="gender">Gender<span style="color: red;">*</span></label>
                    </div>


                    <!-- Added Home Address field -->
                    <div class="form-group">
                        <input type="text" id="home_address" name="home_address" placeholder=" " required>
                        <label for="home_address">Home Address<span style="color: red;">*</span></label>
                    </div>

                    <div class="form-group">
                        <input type="text" id="specialization" name="specialization" placeholder=" " required>
                        <label for="specialization">Specialization<span style="color: red;">*</span></label>
                    </div>

                    <div class="form-group">
                        <input type="text" id="contact_number" name="contact_number" placeholder=" " required>
                        <label for="contact_number">Contact Number<span style="color: red;">*</span></label>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="cancel-btn" onclick="document.getElementById('addTherapistModal').style.display='none'">Cancel</button>
                        <button type="submit" class="save-btn">Create Account</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<!-- Success Modal -->
<div id="successModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Account Created Successfully!</h2>
                </div>
                <div class="bot">
                    <p>Default password for the therapist: <strong id="defaultPassword"></strong></p>
                    <p>Please share this password with the therapist securely.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button class="save-btn" onclick="closeSuccessModal()">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = "none";
    }
}

document.getElementById('addTherapistForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    fetch('/admin/therapists/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(JSON.stringify(errorData));
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('addTherapistModal').style.display = 'none';
            document.getElementById('addTherapistModal').classList.remove('show');
            document.getElementById('defaultPassword').textContent = data.default_password;
            document.getElementById('successModal').style.display = 'block';
            document.getElementById('successModal').classList.add('show');
            this.reset();
        } else {
            alert(data.message || 'Error creating therapist account');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        try {
            const errorData = JSON.parse(error.message);
            if (errorData.errors) {
                // Handle validation errors
                const errorMessages = Object.values(errorData.errors).flat().join('\n');
                alert('Validation errors:\n' + errorMessages);
            } else {
                alert(errorData.message || 'Error creating therapist account');
            }
        } catch (e) {
            alert('Error creating therapist account: ' + error.message);
        }
    });
});


function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
    document.getElementById('successModal').classList.remove('show');
    window.location.reload();
}
</script>

</body>
</html>
</x-admin-layout>