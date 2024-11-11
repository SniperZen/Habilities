document.addEventListener('DOMContentLoaded', function() {
    var viewModal = document.getElementById("appointmentModal");
    var cancelModal = document.getElementById("cancelAppointmentModal");
    var confirmationModal = document.getElementById("ConfirmationModal"); // Confirmation modal
    const cancelAppointmentButtons = document.querySelectorAll('.cancel-appointment-btn');
    const cancelAppointmentButtons2 = document.querySelectorAll('.cancel-appointment-btn2'); // Button in cancel modal
    var viewBtns = document.querySelectorAll(".view-btn");
    var cancelBtns = document.querySelectorAll(".cancel-btn");
    var closeBtns = document.querySelectorAll(".close-btn");
    var backBtns = document.querySelectorAll(".back-btn");
    var yes = document.querySelectorAll(".yes");

    viewBtns.forEach(function(viewBtn) {
        viewBtn.addEventListener("click", function() {
            viewModal.style.display = "flex";
        });
    });

    cancelBtns.forEach(function(cancelBtn) {
        cancelBtn.addEventListener("click", function() {
            viewModal.style.display = "none"; // Hide view modal
            cancelModal.style.display = "flex"; // Show cancel modal
        });
    });


    cancelAppointmentButtons.forEach(function(cancelAppointmentBtn) {
        cancelAppointmentBtn.addEventListener("click", function() {
            viewModal.style.display = "none"; // Hide view modal
            cancelModal.style.display = "flex"; // Show cancel modal
        });
    });

    cancelAppointmentButtons2.forEach(function(cancelAppointmentBtn2) {
        cancelAppointmentBtn2.addEventListener("click", function() {
            cancelModal.style.display = "none"; // Hide cancel modal
            confirmationModal.style.display = "flex"; // Show confirmation modal
        });
    });

    // Close Modals
    closeBtns.forEach(function(closeBtn) {
        closeBtn.addEventListener("click", function() {
            viewModal.style.display = "none";
            cancelModal.style.display = "none";
            confirmationModal.style.display = "none"; // Close confirmation modal
        });
    });

    // Back button functionality to close both cancel and confirmation modals
    backBtns.forEach(function(backBtn) {
        backBtn.addEventListener("click", function() {
            viewModal.style.display = "none";
            cancelModal.style.display = "none";
            confirmationModal.style.display = "none"; // Close confirmation modal
        });
    });

    yes.forEach(function(backBtn) {
        backBtn.addEventListener("click", function() {
            viewModal.style.display = "none";
            cancelModal.style.display = "none";
            confirmationModal.style.display = "none"; // Close confirmation modal
        });
    });

    // Close Modal if clicked outside of content
    window.addEventListener("click", function(event) {
        if (event.target == viewModal) {
            viewModal.style.display = "none";
        } else if (event.target == cancelModal) {
            cancelModal.style.display = "none";
        } else if (event.target == confirmationModal) {
            confirmationModal.style.display = "none"; // Close confirmation modal if clicked outside
        }
    });
});
