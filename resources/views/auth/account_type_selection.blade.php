    <x-guest-layout>
        <style>
            .container {
                display: flex;
                width: 100%;
                height: 100vh;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .image-side {
                flex: 1.5;
                background-image: url('{{ asset('images/bg.png') }}');
                background-size: cover;
                background-position: center;
                height: 100vh; /* Ensure it takes full height */
            }

            .form-side {
                flex: 1;
                background-color: white;
                padding: 100px 80px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
            }

            .form-side img {
                height: 250px;
            }

            .form-side h2 {
                font-size: 36px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 15px;
            }

            .form-side p {
                font-size: 20px;
                color: #666;
                margin-bottom: 30px;
                text-align: center;
            }

            .form-side button {
                background-color: #74A36B;
                color: white;
                border: none;
                height: 50px;
                border-radius: 50px;
                cursor: pointer;
                width: 100%;
                max-width: 570px;
                font-size: 16px;
                font-weight: bold;
                transition: box-shadow 0.3s ease;
                transition: transform 0.3s ease;
                margin-bottom: 8px;
                padding: 0 14px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
                transition: box-shadow 0.3s ease;
                transition: transform 0.3s ease;
            }

            .form-side button:hover {
                transform: scale(1.02);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);

            }

            .form-side a {
                color: #74A36B;
                font-size: 16px;
                text-decoration: none;
                display: block;
                text-align: center;
            }

            .form-side a:hover {
                text-decoration: underline;
            }

            .space-y-4 {
                display: flex;
                flex-direction: column;
                gap: 15px;
                width: 100%;
                max-width: 500px;
            }

            

            .mt-4{
                display: flex;
                align-items: flex-start;
                flex-direction: row;
                margin-top: 15px;
                gap: 5px;
                a, p{
                    font-size: 16px;
                }
            }

            @media (max-width: 768px) {

            .image-side {
                display:none;
            }
            .form-side {
                padding: 40px 20px;
            }

            .form-side h2 {
                font-size: 24px;
            }

            .form-side p {
                font-size: 16px;
            }

            .form-side button {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .image-side {
                display:none;
            }
            .form-side {
                padding: 30px 10px;
            }

            .form-side h2 {
                font-size: 20px;
            }

            .form-side p {
                font-size: 14px;
            }

            .form-side button {
                height: 45px;
                font-size: 12px;
            }
        }
        .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 400px;
    text-align: center;
}

.age-verification-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.age-verification-buttons button {
    max-width: 100px !important;
    margin: 0 !important;
    height: 40px !important;
}

.modal h2 {
    color: #333;
    font-size: 24px !important;
    margin-bottom: 15px !important;
}

.modal p {
    color: #666;
    font-size: 16px !important;
    margin-bottom: 15px !important;
    line-height: 1.5;
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .modal-content {
        margin: 30% auto;
        padding: 20px;
        width: 90%;
    }
    
    .modal h2 {
        font-size: 20px !important;
    }
    
    .modal p {
        font-size: 14px !important;
    }
}

        </style>

        <div class="container">
            <div class="form-side">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto mb-4"> 
                <h2>Create your account</h2>
                <p>Who will use this account?</p>

                <form method="GET" action="{{ route('register') }}">
                    <div class="space-y-4">
                        <button type="submit" name="account_type" value="self">
                            I want to create an account for myself.
                        </button>
                        <button type="submit" name="account_type" value="child">
                            I want to create an account for my child.
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <p> Already have an account?</p>
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                         Sign in
                    </a>
                </div>
            </div>
            
            <div class="image-side"></div>
        </div>
        
<!-- Age Verification Modal -->
<div id="ageVerificationModal" class="modal">
    <div class="modal-content">
        <h2>Age Verification</h2>
        <p>Are you 13 years of age or older?</p>
        <div class="age-verification-buttons">
            <button onclick="confirmAge(true)">Yes</button>
            <button onclick="confirmAge(false)">No</button>
        </div>
    </div>
</div>

<!-- Age Restriction Message Modal -->
<div id="ageRestrictionModal" class="modal">
    <div class="modal-content">
        <h2>Age Restriction</h2>
        <p>You must be 13 years or older to create an account for yourself. Please ask for assistance with your guardian.</p>
        <div class="age-verification-buttons">
            <button onclick="closeAgeRestrictionModal()">Okay</button>
        </div>
    </div>
</div>


<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get the self account button
        const selfButton = document.querySelector('button[value="self"]');
        
        // Add click event listener to the self account button
        selfButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission
            document.getElementById('ageVerificationModal').style.display = 'block';
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const ageVerificationModal = document.getElementById('ageVerificationModal');
            const ageRestrictionModal = document.getElementById('ageRestrictionModal');
            if (event.target === ageVerificationModal) {
                ageVerificationModal.style.display = 'none';
            }
            if (event.target === ageRestrictionModal) {
                ageRestrictionModal.style.display = 'none';
            }
        });
    });

    // Function to handle age verification
    function confirmAge(isOldEnough) {
        const ageVerificationModal = document.getElementById('ageVerificationModal');
        const ageRestrictionModal = document.getElementById('ageRestrictionModal');
        const form = document.querySelector('form');
        
        if (isOldEnough) {
            // If user is 13 or older, proceed with form submission
            ageVerificationModal.style.display = 'none';
            form.submit();
        } else {
            // If user is under 13, show restriction message
            ageVerificationModal.style.display = 'none';
            ageRestrictionModal.style.display = 'block';
        }
    }

    // Function to close the age restriction modal
    function closeAgeRestrictionModal() {
        document.getElementById('ageRestrictionModal').style.display = 'none';
    }
</script>



    </x-guest-layout>
