<x-guest-layout>
    <style>
        body{
            height: 100vh;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('images/bg.png') no-repeat center center fixed;
            background-size: cover;
            overflow-y: auto;
        }

        .form-box {
            background-color: white;
            padding: 25px 40px;
            border-radius: 10px;
            width: auto;
            max-width: 450px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-box img {
            display: block;
            margin: 0 auto 20px;
            max-width: 147px;
        }

        .form-box h1 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-box p.p {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            font-size: 16px
        }

        .input-group,
        .input-groups {
            display: grid;
            gap: 2px;
        }

        .input-group {
            grid-template-columns: repeat(3, 1fr);
        }

        .input-groups {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-box input[type="text"],
        .form-box input[type="email"],
        .form-box input[type="date"],
        .form-box input[type="tel"],
        .form-box input[type="password"],
        .form-box select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-box input[type="text"]:focus,
        .form-box input[type="email"]:focus,
        .form-box input[type="date"]:focus,
        .form-box input[type="tel"]:focus,
        .form-box input[type="password"]:focus,
        .form-box select:focus {
            border: 1px solid #74A36B;
            outline: none; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .full-width {
            grid-column: span 3;
        }

        .form-box button {
            background-color: #74A36B;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        .form-box button:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .form-box a {
            display: block;
            text-align: center;
            text-decoration: none;
            font-size: 16px
        }

        .form-box a:hover {
            text-decoration: underline;
        }

        .exist{
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: center;
            margin-top: 15px;
            gap: 5px;
            font-size: 14px
        }
        

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .error-message {
            position: absolute;
            top: 94%;
            left: 61px;
            width: 100%;
            color: red;
            font-size: 0.775rem;
            font-weight: 500;
            padding-top: 4px;
        }
        .input-container input {
            width: 100%;
            padding: 8px 8px 8px 5px;
            border: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-container input:focus {
            border-bottom: 1px solid #74A36B;
        }
        .input-container label {
            position: absolute;
            top: 47%;
            left: 8px;
            color: #999;
            font-size: 14px;
            transform: translateY(-50%);
            pointer-events: none;
            transition: all 0.3s;
        }

        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label {
            top: 0px;
            left: 8px;
            font-size: 12px;
            color: #74A36B;
            background-color: white;
            width: calc(auto + 10px);
        }

        .dob{
            top:0;
        }

        option{
            font-size: 12px;
        }

        .terms{
            display: flex;
            flex-direction: row;
            justify-content: center;
            color:black;
            gap: 6px;
            p{
                margin: 0;
                font-size: 14px
            }
            span{
                cursor: pointer;
                color: #618a59;
                font-weight: 500;
            }
            span:hover{
                text-decoration: underline;
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 4% auto;
            padding: 20px;
            height: 85vh;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: left;
        }

        .close {
            display: inline-block;
            background-color: #74A36B;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .close3 {
            display: flex;
            justify-content: flex-end;
        }

        .close:hover,
        .close:focus {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        
        .main-modal{
            overflow-y: auto;
            height: 93%;
            width: 100%;
            padding-right: 15px; 
            box-sizing: content-box;
        }

        .close2{
            width: 189px;
            display: flex;
            margin: 13px auto;
            justify-content: center;
        }

        .signin{
            text-decoration: none;
            font-weight: bold;
            p{
                color: #74A36B;
                font-size: 14px;
                font-weight: 500;
            }
            p:hover{
                text-decoration: underline;
            }
        }

        input[type="email"]:-webkit-autofill, input[type="text"]:-webkit-autofill, input[type="tel"]:-webkit-autofill {
            background-color: white !important;
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
            color: black !important;
        }

        .modal h1 {
            font-size: 24px;
            color: #333333;
            text-align: center;
            margin-bottom: 10px;
        }

        .modal h2 {
            font-size: 18px;
            color: #444444;
            margin-top: 20px;
        }

        .modal strong{
            color: #444444;
        }

        .modal p {
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .modal li{
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
            margin-bottom: 5px;
        }

        .last-updated {
            font-style: italic;
            color: #888888;
            text-align: center;
            margin-bottom: 20px;
        }

        .modal a {
            color: #0056b3;
            text-decoration: none;
            cursor: pointer;
            /* text-decoration: underline; */
        }

        .modal a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-box {
                box-shadow: none;
            }
            .input-group {
                grid-template-columns: repeat(2, 1fr);
            }
            .container{
                background: none;
            }
        }

        @media (max-width: 480px) {
            .form-box {
                box-shadow: none;
                height: 100%;
            }
            .container{
                background: none;
                overflow: auto;
            }
            .input-group {
                grid-template-columns: 1fr;
            }
            
            .form-box {
                padding: 20px;
            }

            .form-box h1 {
                font-size: 20px;
            }

            .form-box p {
                font-size: 12px;
            }

            .form-box button {
                font-size: 14px;
            }
        }
    </style>
    <div class="container">
        <div class="form-box">
            <img src="images/logo.png" alt="Logo">
            <h1>Create your account</h1>
            <p class="p">Hello! We would like to know more of you.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="account_type" value="{{ $accountType }}">
                

                <!-- Name Inputs -->
                <div class="input-group">
                    <div class="input-container">
                        <input placeholder=" " id="last_name" type="text" name="last_name" required autofocus autocomplete="family-name">
                        <label for="last_name">Last Name<span style="color: red;">*</span></label>
                        <div class="error-message">{{ $errors->first('last_name') }}</div>
                    </div>
                    <div class="input-container">
                        <input placeholder=" " id="first_name" type="text" name="first_name" required autocomplete="given-name">
                        <label for="first_name">First Name<span style="color: red;">*</span></label>
                        <div class="error-message">{{ $errors->first('first_name') }}</div>
                    </div>
                    <div class="input-container">
                        <input placeholder=" " id="middle_name" type="text" name="middle_name" autocomplete="additional-name">
                        <label for="middle_name">Middle Name</span></label>
                        <div class="error-message">{{ $errors->first('middle_name') }}</div>
                    </div>
                </div>

                <!-- Date of Birth and Gender Inputs -->
                <div class="input-groups">
                    <div class="input-container">
                        <input id="date_of_birth" type="date" name="date_of_birth" max="{{ now()->toDateString() }}" required>
                        <label  for="date_of_birth">Date of Birth<span style="color: red;">*</span></label>
                        <p class="mt-2">{{ $errors->first('date_of_birth') }}</p>
                    </div>
                    <div class="input-container">
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <label style="color: #74A36B; position: absolute;top:0;left: 8px;font-size: 14px;transform: translateY(-50%);pointer-events: none;transition: all 0.3s; background-color:white;" class="gender" for="gender">Gender<span style="color: red;">*</span></label>
                        <p class="mt-2">{{ $errors->first('gender') }}</p>
                    </div>

                    <style>.gender {position: absolute;top: 0;left: 8px;color: #999;font-size: 14px;transform: translateY(-50%);pointer-events: none;transition: all 0.3s;
                    }</style>
                </div>

                <!-- Contact Number and Home Address Inputs -->
                <div class="input-container">
                    <input placeholder=" " id="contact_number" type="tel" name="contact_number" required autocomplete="tel">
                    <label for="contact_number">Contact Number<span style="color: red;">*</span></label>
                    <div class="error-message">{{ $errors->first('contact_number') }}</div>
                </div>
                <div class="input-container">
                    <input placeholder=" " id="home_address" type="text" name="home_address" required autocomplete="street-address">
                    <label for="home_address">Home Address<span style="color: red;">*</span></label>
                    <div class="error-message">{{ $errors->first('home_address') }}</div>
                </div>

                <!-- Email Input -->
                <div class="input-container">
                    <input placeholder=" " id="email" type="email" name="email" required autocomplete="email">
                    <label for="email">Email Address<span style="color: red;">*</span></label>
                    <div class="error-message">{{ $errors->first('email') }}</div>
                </div>

                <!-- Password and Password Confirmation Inputs -->
                <div class="input-container">
                    <input placeholder=" " id="password" type="password" name="password" required autocomplete="new-password">
                    <label for="password">Password<span style="color: red;">*</span></label>
                    <div class="error-message">{{ $errors->first('password') }}</div>
                </div>
                <div class="input-container">
                    <input placeholder=" " id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password_confirmation">Confirm Password<span style="color: red;">*</span></label>
                    <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                </div>

                <!-- Terms and Conditions Checkbox -->
                <div class="terms">
                    <input type="checkbox" name="terms" required>
                    <p>I agree to <span class="open-terms">Terms of Service</span> and <span class="open-privacy">Privacy Policies</span><span style="color: red;">*</span></p>
                </div>

                <!-- Register Button and Sign-In Link -->
                <button type="submit">Register</button>
                <div class="exist">
                    <p>Already have an account?</p>
                    <a class="signin" href="{{ route('login') }}">
                        <p>Sign in</p>
                    </a>
                </div>
            </form>
        </div>
    </div>
 <div id="termsModal" class="modal">
            <div class="modal-content">
                <div class="main-modal">
                    <h1>Terms and Conditions</h1>
                    <p class="last-updated">Last Updated: November 17, 2024</p>

                    <p>Welcome to TherapEase for Habilities Center for Intervention, a platform dedicated to providing easy access to inquiries, information, and appointment reservations for mental health services. Please read these Terms and Conditions carefully before using our website. By accessing or using our services, you agree to comply with and be bound by these Terms and Conditions. If you do not agree to these, please refrain from using our services.</p>
                    
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing or using TherapEase, you accept and agree to be bound by these Terms and Conditions, as well as our Privacy Policy. Users are encouraged to read the <a class="privacy-policy">Privacy Policy</a> carefully, as it outlines how personal information is collected, used, and protected. These Terms and Policies apply to all users, including those who are browsing, submitting inquiries, booking therapy appointments, or accessing additional features of the platform.</p>
                    
                    <h2>2. Eligibility</h2>
                    <p>Our services are intended for individuals of all ages. However, if you are under 18, we encourage parental or guardian involvement to support and guide your experience on the platform.</p>
                    
                    <h2>3. Description of Services</h2>
                    <p>TherapEase offers a platform for mental health inquiries, therapy appointment reservations, teletherapy sessions, and progress tracking. Licensed therapists on our platform provide medical diagnoses, treatment, and ongoing therapeutic feedback to clients as part of their professional services. While we facilitate access to mental health support, all diagnoses and treatments are solely the responsibility of the licensed therapists.</p>
                    
                    <h2>4. User Responsibilities</h2>
                    <p>Users are responsible for providing accurate, complete, and up-to-date information when creating an account, submitting inquiries, booking appointments, and using any other features on the platform. Misrepresentation of any kind may result in termination of access to our services.</p>
                    
                    <h2>5. Inquiries</h2>
                    <p>5.1 Users may submit mental health inquiries to receive guidance and information related to therapy.</p>
                    <p>5.2 An initial inquiry is required before making an appointment reservation, allowing therapists to assess patients more effectively.</p>
                    <p>5.3 Users must provide accurate details about their mental health concerns and, where applicable, attach accurate and relevant documents to support the inquiry.</p>
                    <p>5.4 Inquiries are addressed by licensed therapists who respond based on the information provided.</p>
                    <p>5.5 While inquiries provide initial guidance, they do not replace a full consultation or diagnosis. For a comprehensive assessment, users are encouraged to proceed with a therapy appointment.</p>
                    
                    <h2>6. Appointment Reservations</h2>
                    <p>6.1 Appointments are subject to availability and are confirmed upon notification by our system or therapist. We do not guarantee availability for specific dates or times.</p>
                    <p>6.2 Appointments can be in-person or virtual (teletherapy), depending on the user’s preferences and the therapist’s availability.</p>
                    <p>6.3 To secure an appointment, users must complete any required initial inquiries and provide all necessary information.</p>
                    <p>6.4 Users must comply with any instructions or guidelines provided by the therapists and therapy center for appointments, including but not limited to being punctual and adhering to cancellation policies.</p>
                    <p>6.5 Cancellations must be made within the specified period outlined in the appointment confirmation message to avoid any penalties or being restricted from access to further bookings.</p>
                    
                    <h2>7. Privacy and Confidentiality</h2>
                    <p>We value and prioritize user privacy. Personal information provided by users is handled in accordance with our Privacy Policy and the Data Privacy Act of 2012. We follow industry-standard practices to protect sensitive data, including encryption and secure storage. However, we cannot guarantee absolute security, and users are responsible for keeping their login credentials secure and for any actions taken under their account. Users are encouraged to report any unauthorized access or suspected data breaches.</p>
                    
                    <h2>8. Intellectual Property Rights</h2>
                    <p>All content on this platform, including text, graphics, logos, images, and software, is the property of TheraTech and is protected by intellectual property laws. Unauthorized use of this content is strictly prohibited.</p>
                    
                    <h2>9. Code of Conduct</h2>
                    <p>9.1 Users must engage respectfully and professionally on the platform, including in chat rooms and feedback channels.</p>
                    <p>9.2 Harassment, inappropriate conduct, or sharing of offensive content will not be tolerated and may result in suspension or termination of account access.</p>
                    
                    <h2>10. Limitation of Liability</h2>
                    <p>TherapEase is provided on an "as-is" and "as-available" basis. We make no warranties, either express or implied, regarding the functionality, accuracy, or reliability of the platform. To the fullest extent permitted by law, TherapEase and its affiliates shall not be liable for any damages arising out of or related to your use of or inability to use the platform, including but not limited to direct, indirect, incidental, or consequential damages. We are not responsible for missed or canceled appointments due to technical issues or user errors.</p>
                    
                    <h2>Contact Us</h2>
                    <p>If you have any inquiries, require assistance, or would like to report an issue, please reach out to us:</p>
                    <p>Email: <a href="mailto:hab_cfi@yahoo.com">hab_cfi@yahoo.com</a> or <a href="mailto:lalicruz1977@gmail.com">lalicruz1977@gmail.com</a></p>
                    <p>Phone: +63 927 307 0434</p>
                    <p>Address: 112 Sampaguita Street, Phase 1, Brgy. Bulihan, City Of Malolos, 3000, Bulacan, Philippines</p>
                </div>  
                <div class="close2"><button class="close">Close</button></div>
            </div>
        </div>

        <div id="privacyModal" class="modal">
            <div class="modal-content">
                <div class="main-modal">
                    <h1>Privacy Policy</h1>
                    <p class="last-updated">Last Updated: November 17, 2024</p>

                    <p>Welcome to TherapEase for Habilities Center for Intervention, where we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and safeguard your personal information when you use our website, in compliance with applicable data privacy laws and regulations.</p>
                    
                    <h2>1. Data Privacy Laws</h2>
                    <p>We comply with all applicable data privacy legislation, including but not limited to the Data Privacy Act of 2012 (Republic Act No. 10173) and the Mental Health Act of 2018 (Republic Act No. 11036) of the Philippines. We are dedicated to ensuring that our practices align with these legal obligations to protect your privacy.</p>
                    
                    <h2>2. Privacy Audit</h2>
                    <p>We conduct regular audits to ensure that we are transparent about the personal information we collect. This includes information collected through our website, mobile applications, cookies, and other tracking technologies.</p>
                    
                    <h2>3. Categories of Personal Information</h2>
                    <p>We may collect the following categories of personal information:</p>
                    <ul>
                        <li><strong>Contact Information:</strong> Name, email address, phone number</li>
                        <li><strong>Health Information:</strong> Details related to your mental health— inquiries and therapy needs</li>
                        <li><strong>Demographic Information:</strong> Age, gender, location</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, and usage data through cookies</li>
                    </ul>
                    <p>Sensitive personal information, which requires stricter handling under laws like the amended CCPA and the CDPA, is treated with additional care.</p>
                    
                    <h2>4. Why We Collect Personal Data</h2>
                    <p>We collect personal data to provide our services, including:</p>
                    <ul>
                        <li>Responding to inquiries</li>
                        <li>Making therapy appointment reservations</li>
                        <li>Facilitating teletherapy sessions</li>
                        <li>Tracking progress and providing ongoing support</li>
                    </ul>
                    <p>Each piece of personal data collected is justified under our legal basis for processing, as required by applicable regulations.</p>
                    
                    <h2>5. How We Collect Personal Data</h2>
                    <p>Personal data may be collected directly from users when they:</p>
                    <ul>
                        <li>Create an account</li>
                        <li>Submit inquiries</li>
                        <li>Schedule appointments</li>
                        <li>Use our platform for teletherapy</li>
                    </ul>
                    <p>We also utilize cookies and similar technologies to enhance user experience and gather usage data.</p>
                    
                    <h2>6. How We Use Personal Data</h2>
                    <p>We use your personal data for the following purposes:</p>
                    <ul>
                        <li>Provide and manage our services</li>
                        <li>Communicate with users regarding appointments and inquiries</li>
                        <li>Improve our platform and services</li>
                    </ul>
                    <p>We do not sell, rent, or share personal data with third parties without your consent, except as required by law.</p>
                    
                    <h2>7. Data Security and Safety Practices</h2>
                    <p>We implement industry-standard security measures to protect your personal information from unauthorized access, loss, and misuse, in accordance with the Data Privacy Act of 2012. This includes encryption of sensitive data and secure data storage practices. However, we cannot guarantee absolute security. Users are encouraged to keep their login credentials secure and take precautions when sharing information online.</p>
                    
                    <h2>8. Third-Party Links</h2>
                    <p>Our website may contain links to external websites that are not operated or controlled by us. These third-party links are provided for your convenience and to enhance your experience. This Privacy Policy is not responsible and does not apply to third-party websites, and we encourage you to read the privacy policies of any sites you visit, as they may have different terms regarding the handling of your data.</p>
                    
                    <h2>9. Your Rights</h2>
                    <p>Under Philippine law, you have the following rights regarding your personal data:</p>
                    <ul>
                        <li><strong>Right to Be Informed:</strong> You have the right to know how your personal data is being processed.</li>
                        <li><strong>Right to Access:</strong> You can request access to your personal information.</li>
                        <li><strong>Right to Rectification:</strong> You can request correction of inaccurate or outdated information.</li>
                        <li><strong>Right to Erasure or Blocking:</strong> You may request deletion or blocking of your data under certain circumstances.</li>
                        <li><strong>Right to Object:</strong> You can object to the processing of your personal data.</li>
                        <li><strong>Right to Data Portability:</strong> You have the right to obtain a copy of your data in an electronic or structured format.</li>
                        <li><strong>Right to File a Complaint:</strong> You can file a complaint with the National Privacy Commission if you believe your data privacy rights have been violated.</li>
                    </ul>
                    
                    <h2>10. Retention of Your Personal Data</h2>
                    <p>We retain your personal and sensitive information for as long as necessary to fulfill our purposes. Generally, this information will be retained for up to seven (7) years from your last interaction with our platform, or longer if required by law, to support continuity of care and future service needs. If you request deletion before this period, we will review your request and take appropriate action in accordance with legal and regulatory standards.</p>
                    
                    <h2>11. Privacy Policy Updates</h2>
                    <p>We may update this Privacy Policy periodically to reflect changes in our practices, technology, legal requirements, or other factors. Any changes will be posted on our platform, and we will notify users of significant changes through email or in-app notifications. Your continued use after such updates constitutes your acceptance of the new terms.</p>
                    
                    <h2>12. Contact Us</h2>
                    <p>If you have any inquiries, require assistance, or would like to report an issue regarding this Privacy Policy, please reach out to us:</p>
                    <p>Email: <a href="mailto:hab_cfi@yahoo.com">hab_cfi@yahoo.com</a> or <a href="mailto:lalicruz1977@gmail.com">lalicruz1977@gmail.com</a></p>
                    <p>Phone: +63 927 307 0434</p>
                    <p>Address: 112 Sampaguita Street, Phase 1, Brgy. Bulihan, City Of Malolos, 3000, Bulacan, Philippines</p>
                    
                    <p>Thank you for trusting TherapEase with your mental health needs. Your privacy is important to us.</p>
                </div>  
                    <div class="close2"><button class="close">Close</button></div>
            </div>
        </div>
    <script>
        // Get modals and buttons
        const termsModal = document.getElementById("termsModal");
        const privacyModal = document.getElementById("privacyModal");
        const termsLink = document.querySelector(".open-terms");
        const privacyLink = document.querySelector(".open-privacy");
        const closeButtons = document.querySelectorAll(".close");
        const privacyPolicyLinks = document.querySelectorAll(".privacy-policy");

        // Open Terms Modal
        termsLink.onclick = function() {
            termsModal.style.display = "block";
        };

        // Open Privacy Modal
        privacyLink.onclick = function() {
            privacyModal.style.display = "block";
        };

        // Open Privacy Modal from Privacy Policy links in Terms Modal
        privacyPolicyLinks.forEach(link => {
            link.onclick = function() {
                privacyModal.style.display = "block";
                termsModal.style.display = "none";
            };
        });

        // Close modals when clicking close buttons
        closeButtons.forEach(button => {
            button.onclick = function() {
                termsModal.style.display = "none";
                privacyModal.style.display = "none";
            };
        });

        // Close modals when clicking outside
        //window.onclick = function(event) {
         //   if (event.target == termsModal) {
          //      termsModal.style.display = "none";
         //   }
         //   if (event.target == privacyModal) {
       //         privacyModal.style.display = "none";
        //    }
       // };
    </script>


</x-guest-layout>
