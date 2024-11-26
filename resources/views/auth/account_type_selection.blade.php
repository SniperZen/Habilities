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
.modal-content {
    background-color: white;
    border-radius: 10px;
    width: 450px;
    margin: 15% auto;
    text-align: center;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    transform: scale(0.5);
    animation-fill-mode: forwards;
    animation-timing-function: ease-out; 
    animation-duration: 0.2s; 
}

.modal.show {
    display: block;
    opacity: 1;
}

.modal.show .modal-content {
    animation-name: popUpEffect; 
}

.heads{
    width: 100%;
    background-color: #74A36B;
    height: 15px;
    border-radius: 10px 10px 0 0;
}

.mod-cont{
    padding: 20px;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: 30px;
    border-top: 1px solid #afafaf;
    padding-top: 20px;
    gap: 20px;
}

.top{
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 20px;
    border-bottom: 1px solid #afafaf;
    width: 100%;
    h2{
        margin: 0;
    }
}
.bot {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: justify;
    padding: 0 10px;
}
.inner{
    display: flex;
    gap: 20px;
    flex-direction: column;
    position: relative;
}

.modal h2 {
    margin-top: 0;
    color: #333;

}

.modal p {
    color: #666;
    margin-bottom: 1.5rem;
}

.yes{
    background-color: #74A36B;
    display: inline-block;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    transition: box-shadow 0.3s ease;
    transition: transform 0.3s ease;
}

.no{
    display: inline-block;
    background-color: #ffffff;
    color: #74A36B;
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    transition: box-shadow 0.3s ease;
    transition: transform 0.3s ease;
    border: 1px solid #74A36B;
}

@media (max-width: 480px) {
    .modal-content {
        margin: 30% auto;
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
        <div class="heads"></div>
        <div class="mod-cont">
            <div class="inner">
                <div class="top">
                    <h2>Are you 13 years of age or older?</h2>
                </div>
                <div class="bot">
                    <p>If not please ask your supervised guardian or parent to create an account for you, otherwise please click yes and proceed with the registration</p>
                </div>
            </div>
            <div class="modal-buttons">
                <button class="no" onclick="confirmAge(false)">No</button>
                <button class="yes" onclick="confirmAge(true)">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Age Restriction Message Modal -->
<div id="ageRestrictionModal" class="modal">
    <div class="modal-content">
        <div class="heads"></div>
            <div class="mod-cont">
                <div class="inner">
                    <div class="top">
                        <h2>Age Restriction</h2>
                    </div>
                    <div class="bot">
                        <p>You must be 13 years or older to create an account for yourself. Please ask for assistance with your guardian.</p>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button class="yes" onclick="closeAgeRestrictionModal()">Okay</button>
                </div>
            </div>
    </div>
</div>


<script>
 document.addEventListener('DOMContentLoaded', function() {
    // Get the self account button
    const selfButton = document.querySelector('button[value="self"]');
    
    // Add click event listener to the self account button
    selfButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission
        const ageVerificationModal = document.getElementById('ageVerificationModal');
        ageVerificationModal.classList.add('show'); // Add show class to trigger animation
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const ageVerificationModal = document.getElementById('ageVerificationModal');
        const ageRestrictionModal = document.getElementById('ageRestrictionModal');
        if (event.target === ageVerificationModal) {
            ageVerificationModal.classList.remove('show'); // Remove show class to hide
        }
        if (event.target === ageRestrictionModal) {
            ageRestrictionModal.classList.remove('show');
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
        ageVerificationModal.classList.remove('show'); // Remove show class to hide
        form.submit();
    } else {
        // If user is under 13, show restriction message
        ageVerificationModal.classList.remove('show');
        ageRestrictionModal.classList.add('show'); // Add show class to trigger animation
    }
}

// Function to close the age restriction modal
function closeAgeRestrictionModal() {
    document.getElementById('ageRestrictionModal').classList.remove('show'); // Remove show class to hide
}
</script>



    </x-guest-layout>
