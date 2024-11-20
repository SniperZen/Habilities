<x-patient-layout><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/inquiry2.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>

<div class="tcontainer">

        <main class="main-content">
            <h2>We want to know more!</h2>
            <p>Please upload the documents so we can assist you and connect you to our therapy services.</p>

            <div class="form-container">
                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="step inactive">
                        <div class="step-number">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">2</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step active">
                        <div class="step-number">3</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">4</div>
                        <div class="step-line"></div>
                    </div>
                </div>

                <!-- File Upload Form -->
                <form action="{{ route('patient.storeInquiryStep2') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="form-group">
                        <label for="identification_card">Identification Card (optional)</label>
                        <div class="file-upload">
                            <input type="file" name="identification_card" id="identification_card" accept=".jpg,.png,.pdf,.docx">
                        </div>
                        @error('identification_card')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="birth-certificate">Birth Certificate<span style="color: red;">*</span></label>
                        <div class="file-upload">
                            <input type="file" name="birth_certificate" id="birth-certificate" accept=".jpg,.png,.pdf,.docx" required>
                        </div>
                        @error('birth_certificate')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="diagnosis-reports">Working Diagnosis / Doctor's Reports<span style="color: red;">*</span></label>
                        <div class="file-upload">
                            <input type="file" name="diagnosis_reports" id="diagnosis-reports" accept=".jpg,.png,.pdf,.docx" required>
                        </div>
                        @error('diagnosis_reports')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <small>Max file size is 10MB. Supported file types are .jpg, .png, .pdf, and .docx.</small>

                    <div class="button-group">
                        <a href="{{ route('patient.inquiry') }}" class="button">Back</a>
                        <button type="submit" class="button">Upload</button>
                    </div>
                </form>

            </div>
        </main>
    </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadForm');
    const maxSize = 10 * 1024 * 1024; // 10MB in bytes
    const allowedTypes = ['.jpg', '.jpeg', '.png', '.pdf', '.docx'];

    function validateFile(file) {
        if (!file) return { valid: true };

        // Check file size
        if (file.size > maxSize) {
            return {
                valid: false,
                error: `File "${file.name}" is too large. Maximum size is 10MB.`
            };
        }

        // Check file type
        const extension = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowedTypes.includes(extension)) {
            return {
                valid: false,
                error: `File "${file.name}" has an invalid type. Allowed types are: jpg, png, pdf, and docx.`
            };
        }

        return { valid: true };
    }

    form.addEventListener('submit', function(e) {
        const idCard = document.getElementById('identification_card').files[0];
        const birthCert = document.getElementById('birth-certificate').files[0];
        const diagnosisReport = document.getElementById('diagnosis-reports').files[0];

        // Validate each file
        const idCardValidation = validateFile(idCard);
        const birthCertValidation = validateFile(birthCert);
        const diagnosisValidation = validateFile(diagnosisReport);

        if (!idCardValidation.valid || !birthCertValidation.valid || !diagnosisValidation.valid) {
            e.preventDefault();
            
            const errors = [
                idCardValidation.error,
                birthCertValidation.error,
                diagnosisValidation.error
            ].filter(error => error);

            errors.forEach(error => {
                Toastify({
                    text: error,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true,
                    close: true,
                }).showToast();
            });
        }
    });

    // Add change event listeners to show immediate feedback
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const validation = validateFile(file);
                if (!validation.valid) {
                    e.target.value = ''; // Clear the invalid file
                    Toastify({
                        text: validation.error,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                        stopOnFocus: true,
                        close: true,
                    }).showToast();
                }
            }
        });
    });
});
</script>
<style>
.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.file-upload {
    margin-bottom: 0.5rem;
}

.file-upload input[type="file"] {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.file-upload input[type="file"]:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
</style>

</body>
</html>
</x-patient-layout>