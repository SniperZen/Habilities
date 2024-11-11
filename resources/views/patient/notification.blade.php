<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Panel UI</title>
    <link rel="stylesheet" href="{{ asset('css/patient/notif.css')}}">
</head>
<body>

<div class="container">
    <main class="main-content">
    <div class="notifications-card">
        <div class="notifications-header">Notifications</div>

        <!-- Notification Item 1 -->
        <div class="notification-item" data-read="false">
            <img src="{{ asset('images/others/default-prof.png') }}" alt="Profile 1" class="notification-avatar">
            <div class="notification-info">
                <p class="notification-title">Ethan C. Elizalde</p>
                <p class="notification-description">scheduled an appointment.</p>
            </div>
            <span class="unread-indicator"></span>
            <div class="more-options">⋮</div>
            <div class="dropdown-menu">
                <button class="toggle-read">Mark as Read</button>
                <button class="delete-notification">Delete</button>
            </div>
        </div>

        <!-- Notification Item 2 -->
        <div class="notification-item" data-read="false">
            <img src="{{ asset('images/others/default-prof.png') }}" alt="Profile 2" class="notification-avatar">
            <div class="notification-info">
                <p class="notification-title">Habilities Center for Intervention</p>
                <p class="notification-description">has a new update.</p>
            </div>
            <span class="unread-indicator"></span>
            <div class="more-options">⋮</div>
            <div class="dropdown-menu">
                <button class="toggle-read">Mark as Read</button>
                <button class="delete-notification">Delete</button>
            </div>
        </div>

        <!-- Notification Item 3 -->
        <div class="notification-item" data-read="false">
            <img src="{{ asset('images/others/default-prof.png') }}" alt="Profile 3" class="notification-avatar">
            <div class="notification-info">
                <p class="notification-title">Benjamin A. Ilustre</p>
                <p class="notification-description">sent you a message.</p>
            </div>
            <span class="unread-indicator"></span>
            <div class="more-options">⋮</div>
            <div class="dropdown-menu">
                <button class="toggle-read">Mark as Read</button>
                <button class="delete-notification">Delete</button>
            </div>
        </div>

        <!-- Notification Item 4 -->
        <div class="notification-item" data-read="false">
            <img src="{{ asset('images/others/default-prof.png') }}" alt="Profile 4" class="notification-avatar">
            <div class="notification-info">
                <p class="notification-title">Habilities Center for Intervention</p>
                <p class="notification-description">You have an upcoming tele-therapy session.</p>
            </div>
            <span class="unread-indicator"></span>
            <div class="more-options">⋮</div>
            <div class="dropdown-menu">
                <button class="toggle-read">Mark as Read</button>
                <button class="delete-notification">Delete</button>
            </div>
        </div>

    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this notification?</p>
        <div class="modal-actions">
            <button id="confirmDelete" class="confirm-btn">Yes</button>
            <button id="cancelDelete" class="cancel-btn">No</button>
        </div>
    </div>
    </main>
</div>

<script>
    let notificationToDelete = null;

    document.querySelectorAll('.more-options').forEach(function(moreOptionsBtn) {
        moreOptionsBtn.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                menu.style.display = 'none';
            });

            const dropdown = this.nextElementSibling;
            const buttonRect = moreOptionsBtn.getBoundingClientRect();
            dropdown.style.display = 'block';
            dropdown.style.position = 'absolute';
            dropdown.style.top = `${buttonRect.top + window.scrollY}px`; 
            dropdown.style.left = `${buttonRect.right + window.scrollX}px`;
        });
    });

    document.querySelectorAll('.delete-notification').forEach(function(button) {
        button.addEventListener('click', function() {
            notificationToDelete = this.closest('.notification-item'); 
            document.getElementById('deleteModal').style.display = 'flex'; 
        });
    });

    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (notificationToDelete) {
            notificationToDelete.remove(); 
            notificationToDelete = null; 
            document.getElementById('deleteModal').style.display = 'none'; 
        }
    });

   
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'none'; 
        notificationToDelete = null; 
    });

    
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            modal.style.display = 'none';
            notificationToDelete = null; 
        }
    });

    
    document.querySelectorAll('.toggle-read').forEach(function(button) {
        button.addEventListener('click', function() {
            const notificationItem = this.closest('.notification-item');
            const isRead = notificationItem.getAttribute('data-read') === 'true';

    
            if (isRead) {
                this.textContent = 'Mark as Read';
                notificationItem.setAttribute('data-read', 'false');
                notificationItem.querySelector('.unread-indicator').style.backgroundColor = '#1877f2'; 
            } else {
                this.textContent = 'Mark as Unread';
                notificationItem.setAttribute('data-read', 'true');
                notificationItem.querySelector('.unread-indicator').style.backgroundColor = 'transparent'; 
            }

            this.parentElement.style.display = 'none';
        });
    });

    document.addEventListener('click', function(event) {
        if (!event.target.matches('.more-options')) {
            document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                menu.style.display = 'none';
            });
        }
    });
</script>

</body>
</html>

</x-patient-layout>