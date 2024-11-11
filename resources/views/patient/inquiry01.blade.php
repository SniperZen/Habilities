<x-patient-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/inquiry01.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Show modals when the highlighted terms are clicked
document.getElementById("privacyLink").onclick = function() {
    document.getElementById("privacyModal").style.display = "flex";
};

document.getElementById("termsLink").onclick = function() {
    document.getElementById("termsModal").style.display = "flex";
};

// Close modals when 'close' is clicked
document.querySelectorAll(".close").forEach(closeBtn => {
    closeBtn.onclick = function() {
        closeBtn.closest(".modal").style.display = "none";
    };
});

    </script>
</head>
<body>
    <div class="tcontainer">
        <main class="main-content">
            <h2>Review and Acknowledge Our Policies</h2>
            <p class="p">Please review our Terms and Privacy Policy. By proceeding, you confirm your understanding and acceptance, ensuring a secure experience with our services.</p>

            <div class="form-container">
                <!-- Progress Bar -->
                 <form action="{{ route('patient.inquiry') }}">
                <div class="progress-bar">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">2</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">3</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">4</div>
                        <div class="step-line"></div>
                    </div>
                </div>
                    <div class="body">
                    <div class="terms" id="terms">
                    <h1>Terms and Conditions</h1>
                    <p class="last-updated">Last Updated: November 17, 2024</p>

                    <p>Welcome to TherapEase for Habilities Center for Intervention, a platform dedicated to providing easy access to inquiries, information, and appointment reservations for mental health services. Please read these Terms and Conditions carefully before using our website. By accessing or using our services, you agree to comply with and be bound by these Terms and Conditions. If you do not agree to these, please refrain from using our services.</p>
                    
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing or using TherapEase, you accept and agree to be bound by these Terms and Conditions, as well as our Privacy Policy. Users are encouraged to read the <a href="#privacy-policy">Privacy Policy</a> carefully, as it outlines how personal information is collected, used, and protected. These Terms and Policies apply to all users, including those who are browsing, submitting inquiries, booking therapy appointments, or accessing additional features of the platform.</p>
                    
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
                    <div class="privacy" id="privacy-policy">
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

                    <div class="consent-checkbox">
                        <input type="checkbox" id="agree" required>
                        <label for="agree">
                            By checking this box, I acknowledge that I have read and understood the importance of 
                            <a href="#privacy-policy"><span class="highlight" id="privacyLink" style="color:#395886; font-weight: bold;">Privacy Policy</span></a> and 
                            <a href="#terms"><span class="highlight" id="termsLink" style="color:#395886; font-weight: bold;">Terms and Conditions</span></a>. <span style="color: red;">*</span>
                        </label>
                    </div>
                    </div>

                    
                    <div class="but"><button type="submit" class="button">Next</button></div>
                    </form>
        </main>
    </div>

    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>
</html>
    </x-patient-layout>