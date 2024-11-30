<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
    [x-cloak] { display: none !important; }
    
    /* Add these new classes */
    .modal-deactivate {
        z-index: 1200; /* Highest z-index */
    }
    .modal-usertype {
        z-index: 1100;
    }
    .modal-success {
        z-index: 1150;
    }
    
    /* Make sure the details section has a lower z-index */
    .showDetails {
        position: relative;
        z-index: 900;
    }
    .modal-activate {
        z-index: 1200;
    }
    
    .modal-save {
        z-index: 1200;
    }
    
    .modal-content h2 {
        margin-bottom: 1rem;
        color: #333;
    }

    #gender label{
        top: 9px;
        left: 8px;
        font-size: 12px;
        color: #74A36B;
        background-color: white;
        padding: 0 5px;
    }
    
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
</head>
<body>
<div x-cloak x-data="{ 
    showDetails: false, 
    patient: null, 
    editMode: false, 
    showDeactivateModal: false,
    showUserTypeModal: false,
    showSuccessModal: false,
    showActivateModal: false,      // Add this
    showSaveChangesModal: false,   // Add this
    tempUserType: '',
    selectedPatientId: null,
    expandPatient(patientData) {
        this.patient = patientData;
        this.showDetails = true;
    }
}">




    <div class="dashboard-container">
        <div class="int">
    <div x-show="!showDetails">
        <h2>Manage Users &gt; Patients</h2>
        <div class="search_area">
            <input id="searchInput" class="search" type="text" placeholder="Search for Patient..." autocomplete="off"/>
            <button id="searchButton" class="but"><img src="{{asset('images/icons/search.png')}}" alt=""></button>
            <button id="refreshButton" class="but refresh"><img src="{{asset('images/icons/refresh.png')}}" alt=""></button>
        </div>
        <table id="patientsTable">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Patient ID</th>
                    <!--<th>User Level</th>-->
                    <th>Account Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->name }}</td>
                        <td>{{ 'P-000' . $patient->id }}</td>
                        <!--<td>
                        <div class="select-wrapper">
                        <select class="user-type-select" 
                                @change="
                                    showUserTypeModal = true; 
                                    tempUserType = $event.target.value; 
                                    selectedPatientId = {{ $patient->id }};"
                                data-original-value="{{ $patient->usertype }}"
                                data-patient-id="{{ $patient->id }}">
                            <option value="user" {{ $patient->usertype == 'user' ? 'selected' : '' }}>Patient</option>
                            <option value="therapist" {{ $patient->usertype == 'therapist' ? 'selected' : '' }}>Therapist</option>
                        </select>
                        </div>
                        </td>-->
                        <td>
                            <span class="status {{ $patient->account_status == 'active' ? 'active' : 'deactivated' }}">
                                {{ ucfirst($patient->account_status) }}
                            </span>
                        </td>
                        <td>
                            <button class="expand" @click="expandPatient({{ json_encode($patient) }})">
                                Expand
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Patient Details Section -->
    <div x-show="showDetails" class="showDetails">
        <div class="profile-card">
        <img :src="patient && patient.profile_image ? '{{ Storage::url('') }}' + patient.profile_image : '{{asset('images/others/default-prof.png')}}'" :alt="patient ? patient.name : 'Default Profile'">
            <h3 x-text="patient ? patient.name : ''"></h3>
            <p x-text="'Patient ID: P-000' + (patient ? patient.id : '')"></p>
            <p x-text="'Email: ' + patient.email"></p>
            <p x-show="patient && patient.account_type === 'child'" class="supervised-badge">
                Supervised Account
            </p>
            <p x-show="patient && patient.account_type === 'child'"><strong>Supervised by:</strong></p>
            <p x-show="patient && patient.account_type === 'child'" x-text="patient.email"></p>
            <button class="button" 
                :class="patient && patient.account_status === 'active' ? 'button-danger' : 'button-primary'" 
                @click="patient && patient.account_status === 'active' ? showDeactivateModal = true : showActivateModal = true">
                <span x-text="patient && patient.account_status === 'active' ? 'Deactivate Account' : 'Activate Account'"></span>
            </button>

              
            <button class="back" @click="showDetails = false">Back to List</button>
        </div>

        <div class="account-info">
            <div class="inf">
            <h2>Account Information</h2>
            <form 
                x-ref="userForm"
                method="POST" 
                x-bind:action="'/admin/updateUser/' + (patient ? patient.id : '')"
                @submit.prevent="showSaveChangesModal = true">
                @csrf
                @method('PATCH')
                <input type="hidden" name="user_id" x-bind:value="patient ? patient.id : ''">

                <div class="form-group">
                    <input type="text" id="first_name" name="first_name" x-bind:value="patient ? patient.first_name : ''" required>
                    <label for="first_name">First Name</label>
                </div>
                <div class="form-group">
                    <input type="text" id="middle_name" name="middle_name" x-bind:value="patient ? patient.middle_name : ''">
                    <label for="middle_name">Middle Name</label>
                </div>
                <div class="form-group">
                    <input type="text" id="last_name" name="last_name" x-bind:value="patient ? patient.last_name : ''" required>
                    <label for="last_name">Last Name</label>
                </div>
                <div class="form-group">
                    <input type="date" id="date_of_birth" name="date_of_birth" x-bind:value="patient ? formatDate(patient.date_of_birth) : ''">
                    <label for="date_of_birth">Birthday</label>
                </div>
                <div class="form-group gender">
                    <select id="gender" name="gender">
                        <option value="male" x-bind:selected="patient && patient.gender === 'male'">Male</option>
                        <option value="female" x-bind:selected="patient && patient.gender === 'female'">Female</option>
                        <option value="other" x-bind:selected="patient && patient.gender === 'other'">Other</option>
                    </select>
                    <label for="gender">Gender</label>
                </div>
                <div class="form-group">
                    <input type="text" id="contact_number" name="contact_number" x-bind:value="patient ? patient.contact_number : ''">
                    <label for="contact_number">Contact Number</label>
                </div>
                <div class="form-group">
                    <input type="text" id="home_address" name="home_address" x-bind:value="patient ? patient.home_address : ''">
                    <label for="home_address">Address</label>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" @click="editMode = false; showDetails = false;">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const refreshButton = document.getElementById('refreshButton');
    const patientsTable = document.getElementById('patientsTable');
    const rows = Array.from(patientsTable.getElementsByTagName('tr')).slice(1); // Exclude header row

    function searchPatients() {
        const filter = searchInput.value.toLowerCase();
        rows.forEach(row => {
            const nameCell = row.cells[0];
            const name = nameCell.textContent || nameCell.innerText;
            row.style.display = name.toLowerCase().includes(filter) ? '' : 'none';
        });
    }

    function resetSearch() {
        searchInput.value = '';
        rows.forEach(row => row.style.display = '');
    }

    function refreshPage() {
        resetSearch();
        window.location.reload();
    }

    searchButton.addEventListener('click', searchPatients);
    searchInput.addEventListener('input', searchPatients);
    refreshButton.addEventListener('click', refreshPage);

    // Handle user type changes
    //document.querySelectorAll('.user-type-select').forEach(select => {
     //   select.addEventListener('change', function() {
     //       const patientId = this.dataset.patientId;
     //       const newUserType = this.value;
     //       updateUserType(patientId, newUserType);
     //   });
    //});

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function deactivateAccount(userId) {
        fetch(`/admin/users/${userId}/deactivate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
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
            window.location.reload();
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
                window.location.reload();
            } else {
                alert('Failed to update user type.');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }

    // Make these functions available globally
    window.deactivateAccount = deactivateAccount;
    window.activateAccount = activateAccount;
    window.updateUserType = updateUserType;
    window.formatDate = formatDate;
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.Alpine.data('userData', () => ({
        showUserTypeModal: false,
        showSuccessModal: false,
        tempUserType: '',
        selectedPatientId: null,
        patient: null,

        async confirmUserTypeChange() {
            try {
                const response = await fetch(`/admin/users/${this.selectedPatientId}/update-usertype`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ usertype: this.tempUserType })
                });

                if (response.ok) {
                    this.showUserTypeModal = false;
                    this.showSuccessModal = true;
                    // Reload page after successful update
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error('Failed to update user type');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating user type.');
            }
        }
    }));
});
</script>
<!-- User Type Change Confirmation Modal -->
<div x-show="showUserTypeModal" class="modal modal-deactivate" x-cloak>
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
                            fetch(`/admin/users/${selectedPatientId}/update-usertype`, {
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
                <button @click="deactivateAccount(patient.id); showDeactivateModal = false" class="save-btn">Confirm</button>            
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
                <button @click="activateAccount(patient.id); showActivateModal = false" class="save-btn">Confirm</button>            
            </div>
        </div>
    </div>
</div>

<!-- Save Changes Confirmation Modal -->
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
                    <button @click="$refs.userForm.submit(); showSaveChangesModal = false" class="save-btn">Confirm</button>            
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>


</body>
</html>
</x-admin-layout>
