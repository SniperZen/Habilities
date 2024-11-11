<div id="appointmentModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Appointment Details</h3>
        <p><label>Therapist Name:</label> <span id="modalTherapistName"></span></p>
        <p><label>Date:</label> <span id="modalDate"></span></p>
        <p><label>Time:</label> <span id="modalTime"></span></p>
        <p><label>Type of Therapy:</label> <span id="modalTherapyType"></span></p>
        <p><label>Status:</label> <span id="modalStatus"></span></p>
        <p><label>Mode of Appointment:</label> 
            <input type="radio" name="mode" id="modalModeOnsite" disabled> On-site
            <input type="radio" name="mode" id="modalModeTele" disabled> Tele-Therapy
        </p>
        <p><label>Tele-therapy link:</label> 
            <input id="modalTeleLink" disabled type="text">
        </p>
        <div class="modal-footer">
            <button class="back-btn">Back</button>
            <button class="cancel-appointment-btn">Cancel Appointment</button>
        </div>
    </div>
</div>
