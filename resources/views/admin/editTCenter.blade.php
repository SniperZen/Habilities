<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="{{ asset('css/admin/editTCenter.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <div class="ETC-container">
        <div class="ETC-header" style="background: url('{{ asset('images/awbg.png') }}') center center/cover no-repeat;">
            <div class="image-container">
            <svg alt="Camera" class="camera-icon" width="150" height="150" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="50" fill="black" fill-opacity="0.7"/>
                <path d="M39.9983 35.9987V36.9987C40.3539 36.9987 40.6828 36.8098 40.8621 36.5026L39.9983 35.9987ZM43.9983 29.1416V28.1416C43.6426 28.1416 43.3137 28.3305 43.1345 28.6377L43.9983 29.1416ZM58.8554 29.1416L59.7192 28.6377C59.54 28.3305 59.2111 28.1416 58.8554 28.1416V29.1416ZM62.8554 35.9987L61.9916 36.5026C62.1708 36.8098 62.4997 36.9987 62.8554 36.9987V35.9987ZM29.5697 65.1416V41.713H27.5697V65.1416H29.5697ZM34.284 36.9987H39.9983V34.9987H34.284V36.9987ZM40.8621 36.5026L44.8621 29.6455L43.1345 28.6377L39.1345 35.4949L40.8621 36.5026ZM43.9983 30.1416H58.8554V28.1416H43.9983V30.1416ZM57.9916 29.6455L61.9916 36.5026L63.7192 35.4949L59.7192 28.6377L57.9916 29.6455ZM62.8554 36.9987H68.5697V34.9987H62.8554V36.9987ZM73.284 41.713V65.1416H75.284V41.713H73.284ZM73.284 65.1416C73.284 67.7452 71.1733 69.8559 68.5697 69.8559V71.8559C72.2779 71.8559 75.284 68.8498 75.284 65.1416H73.284ZM68.5697 36.9987C71.1733 36.9987 73.284 39.1094 73.284 41.713H75.284C75.284 38.0048 72.2779 34.9987 68.5697 34.9987V36.9987ZM29.5697 41.713C29.5697 39.1094 31.6804 36.9987 34.284 36.9987V34.9987C30.5758 34.9987 27.5697 38.0048 27.5697 41.713H29.5697ZM34.284 69.8559C31.6804 69.8559 29.5697 67.7452 29.5697 65.1416H27.5697C27.5697 68.8498 30.5758 71.8559 34.284 71.8559V69.8559ZM58.9983 51.9987C58.9983 56.1803 55.6084 59.5702 51.4268 59.5702V61.5702C56.713 61.5702 60.9983 57.2849 60.9983 51.9987H58.9983ZM51.4268 59.5702C47.2453 59.5702 43.8554 56.1803 43.8554 51.9987H41.8554C41.8554 57.2849 46.1407 61.5702 51.4268 61.5702V59.5702ZM43.8554 51.9987C43.8554 47.8172 47.2453 44.4273 51.4268 44.4273V42.4273C46.1407 42.4273 41.8554 46.7126 41.8554 51.9987H43.8554ZM51.4268 44.4273C55.6084 44.4273 58.9983 47.8172 58.9983 51.9987H60.9983C60.9983 46.7126 56.713 42.4273 51.4268 42.4273V44.4273ZM68.5697 69.8559H34.284V71.8559H68.5697V69.8559Z" fill="white"/>
            </svg>
            </div>
            <h1>Contact Us</h1>
                <button type="button" class="save-btn" id="saveBusinessHours">
                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.25 26.25V16.25H8.75V26.25M8.75 3.75V10H18.75M23.75 26.25H6.25C5.58696 26.25 4.95107 25.9866 4.48223 25.5178C4.01339 25.0489 3.75 24.413 3.75 23.75V6.25C3.75 5.58696 4.01339 4.95107 4.48223 4.48223C4.95107 4.01339 5.58696 3.75 6.25 3.75H20L26.25 10V23.75C26.25 24.413 25.9866 25.0489 25.5178 25.5178C25.0489 25.9866 24.413 26.25 23.75 26.25Z" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>Save Changes
                </button>        
        </div>


        <div class="content">
            <div class="contact-form">
                <h2>Habilities Center For Intervention</h2>
                <form id="businessHoursForm">
                    @csrf
                    <div class="contact-info">
                        <!--<label for="address">Address</label>
                         <input type="text" id="address" name="address" value="112 Sampaguita Street, Phase 1, Brgy. Bulihan, City Of Malolos, 3000 Bulacan, Philippines">-->
                        <h3>Contact Information</h3>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="text" id="phone" name="phone" value="{{ $settings->phone ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="{{ $settings->email ?? '' }}">
                        </div>
                    </div>

                    <h3>Business Hours</h3>
                    <div class="hours">
                        @php
                            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                            $businessHours = $settings->business_hours ?? [];
                        @endphp

                        @foreach($days as $day)
                            <div class="day">
                                <label for="{{ $day }}">{{ ucfirst($day) }}:</label>
                                <input type="time" 
                                    id="{{ $day }}StartTime" 
                                    name="hours[{{ $day }}][start_time]" 
                                    value="{{ $businessHours[$day]['start_time'] ?? '' }}"
                                    {{ ($businessHours[$day]['is_closed'] ?? true) || ($businessHours[$day]['is_teletherapy'] ?? false) ? 'disabled' : '' }}
                                > 
                                to 
                                <input type="time" 
                                    id="{{ $day }}EndTime" 
                                    name="hours[{{ $day }}][end_time]" 
                                    value="{{ $businessHours[$day]['end_time'] ?? '' }}"
                                    {{ ($businessHours[$day]['is_closed'] ?? true) || ($businessHours[$day]['is_teletherapy'] ?? false) ? 'disabled' : '' }}
                                >
                                <input type="checkbox" 
                                    id="{{ $day }}Closed" 
                                    name="hours[{{ $day }}][is_closed]"
                                    {{ ($businessHours[$day]['is_closed'] ?? true) ? 'checked' : '' }}
                                > Closed
                                <input type="checkbox" 
                                    id="{{ $day }}Teletherapy" 
                                    name="hours[{{ $day }}][is_teletherapy]"
                                    {{ ($businessHours[$day]['is_teletherapy'] ?? false) ? 'checked' : '' }}
                                > Tele-therapy
                            </div>
                        @endforeach

                    </div>
                </form>
            </div>
            
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.3603880628225!2d120.80403717587748!3d14.861113170643938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339653df18a2d6a5%3A0xac6ca8ce5b8e66fb!2sHabilities%20Center%20For%20Intervention!5e0!3m2!1sen!2sus!4v1725507566462!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal -->
<div id="confirmationModal" class="modal" style="display: none;">
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
                    <h2>Confirm Changes</h2>
                </div>
                <div class="bot">
                    <p>Are you sure you want to save these changes?</p>
                </div>
            </div>
            <div class="modal-buttons">
                <button id="cancelSave" class="cancel-btn">Cancel</button>
                <button id="confirmSave" class="confirm-btn">Yes, Save Changes</button>
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
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="green" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                        <path d="M9 12l2 2l4-4" stroke="green" stroke-width="2" fill="none" />
                    </svg>
                    <h2>Success!</h2>
                </div>
                <div class="bot">
                    <p>Your changes have been saved successfully.</p>
                </div>
                <div class="modal-buttons">
                    <button id="okButton" class="confirm-btn">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmationModal = document.getElementById('confirmationModal');
    const successModal = document.getElementById('successModal');
    
// Update the checkbox event listener section
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const day = this.id.replace('Closed', '').replace('Teletherapy', '').toLowerCase();
        const startTime = document.getElementById(day + 'StartTime');
        const endTime = document.getElementById(day + 'EndTime');
        const isClosed = document.getElementById(day + 'Closed');
        const isTeletherapy = document.getElementById(day + 'Teletherapy');
        
        // If this is the Closed checkbox
        if (this.id.includes('Closed')) {
            if (this.checked) {
                startTime.disabled = true;
                endTime.disabled = true;
                isTeletherapy.checked = false;
            } else {
                startTime.disabled = isTeletherapy.checked;
                endTime.disabled = isTeletherapy.checked;
            }
        }
        
        // If this is the Teletherapy checkbox
        if (this.id.includes('Teletherapy')) {
            if (this.checked) {
                startTime.disabled = true;
                endTime.disabled = true;
                isClosed.checked = false;
            } else {
                startTime.disabled = isClosed.checked;
                endTime.disabled = isClosed.checked;
            }
        }
    });
});


    // Show confirmation modal when Save Changes is clicked
    document.getElementById('saveBusinessHours').addEventListener('click', function() {
        confirmationModal.style.display = 'flex';
    });

    // Handle cancel button in confirmation modal
    document.getElementById('cancelSave').addEventListener('click', function() {
        confirmationModal.style.display = 'none';
    });

    // Handle confirm save in confirmation modal
    document.getElementById('confirmSave').addEventListener('click', function() {
        const form = document.getElementById('businessHoursForm');
        const formData = new FormData(form);
        const formObject = {};
        
        // Convert FormData to a proper object structure
        formData.forEach((value, key) => {
            if (key.includes('[') && key.includes(']')) {
                const matches = key.match(/([^\[\]]+)/g);
                if (matches.length === 3) {
                    if (!formObject[matches[0]]) formObject[matches[0]] = {};
                    if (!formObject[matches[0]][matches[1]]) formObject[matches[0]][matches[1]] = {};
                    formObject[matches[0]][matches[1]][matches[2]] = value;
                } else {
                    if (!formObject[matches[0]]) formObject[matches[0]] = {};
                    formObject[matches[0]][matches[1]] = value;
                }
            } else {
                formObject[key] = value;
            }
        });

        fetch('{{ route("admin.updateCenter") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formObject)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            confirmationModal.style.display = 'none';
            successModal.style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            confirmationModal.style.display = 'none';
        });
    });

    // Handle OK button in success modal
    document.getElementById('okButton').addEventListener('click', function() {
        successModal.style.display = 'none';
        location.reload(); // Reload page to show updated data
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === confirmationModal) {
            confirmationModal.style.display = 'none';
        }
        if (event.target === successModal) {
            successModal.style.display = 'none';
            location.reload();
        }
    });
});

</script>
</html>
</x-admin-layout>
