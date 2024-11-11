<x-patient-layout><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/inquiry2.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
                <form action="{{ route('patient.storeInquiryStep2') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="id-card">Identification Card (optional)</label>
                        <div class="file-upload">
                            <input type="file" name="identification_card" id="identification_card" accept=".jpg,.png,.pdf,.docx">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="birth-certificate">Birth Certificate</label>
                        <div class="file-upload">
                            <input type="file" name="birth_certificate" id="birth-certificate" accept=".jpg,.png,.pdf,.docx" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diagnosis-reports">Working Diagnosis / Doctor's Reports</label>
                        <div class="file-upload">
                            <input type="file" name="diagnosis_reports" id="diagnosis-reports" accept=".jpg,.png,.pdf,.docx" required>
                        </div>
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
</body>
</html>
</x-patient-layout>