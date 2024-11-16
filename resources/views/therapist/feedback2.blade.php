<x-therapist-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Therapy Feedback</title>
        
        <!-- External CSS -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/therapist/feedback2.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    </head>
    <body>
        <div class="feedback-form">
            <div class="content">
                <h1>Therapy Feedback</h1>
                <form id="feedbackForm" method="POST" action="{{ route('feedback.store') }}">
                    @csrf 
                    <div class="top-box">
                        <div class="form-group">
                            <label class="ftitle" for="feedback-title">Feedback Title:</label>
                            <input type="text" id="feedback-title" name="title" placeholder="Type here..." required autocomplete="off">
                        </div>
                        <div class="form-group relative-position">
                            <label for="recipient">Recipient:</label>
                            <input type="hidden" id="recipient-id" name="recipient_id">
                            <input type="text" id="recipient" placeholder="Search Recipient" autocomplete="off">
                            <div id="recipient-dropdown" class="dropdown-list"></div>
                        </div>
                        <div class="custom-select-wrapper">
                            <div class="form-group">
                                <label class="dignosis" for="feedback-diagnosis">Diagnosis:</label>
                                <select id="feedback-diagnosis" name="diagnosis">
                                    <option value="">Select...</option>
                                    <option value="Autism">Autism</option>
                                    <option value="Attention Deficit Hyperactivity Disorder (ADHD)">Attention Deficit Hyperactivity Disorder (ADHD)</option>
                                    <option value="Down Syndrome">Down Syndrome</option>
                                    <option value="Global Developmental Delay (GDD)">Global Developmental Delay (GDD)</option>
                                    <option value="Behavioral Problems">Behavioral Problems</option>
                                    <option value="Learning Disabilities">Learning Disabilities</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div id="other-diagnosis-div" style="display: none; margin-top: 10px;">
                                    <input type="text" id="other-diagnosis" placeholder="Please specify diagnosis">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group1">
                        <label for="feedback-content">Feedback Content:</label>
                        <div id="editor" style="height: 300px;"></div>
                        <input type="hidden" name="content" id="feedback-content">
                    </div>
                    <div class="form-actions">
                        <button type="button" id="back">Back</button>
                        <button type="button" id="send" onclick="showConfirmModal()">Send to Recipient</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <div class="heads"></div>
                <div class="mod-cont">
                    <div class="inner">
                        <div class="top">
                            <h2>Confirm Submission</h2>
                        </div>
                        <div class="bot">
                            <p>Are you sure you want to send this feedback?</p>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <button class="modal-btn cancel-btn" onclick="hideConfirmModal()">Cancel</button>
                        <button class="modal-btn confirm-btn" onclick="submitForm()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill-image-drop-and-paste@1.2.0/dist/quill.imageDropAndPaste.min.js"></script>
        
        <script>
            // Initialize Quill editor
            var quill = new Quill('#editor', {
                theme: 'snow',
                formats: [
                    'bold', 'italic', 'underline',
                    'list', 'bullet',  // Enable lists
                    'indent', 'align'  // Enable indentation
                ],
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ]
                }
            });


            // Modal functions
            function showConfirmModal() {
                // Validate form
                let title = document.getElementById('feedback-title').value.trim();
                let recipientId = document.getElementById('recipient-id').value;
                let content = quill.root.innerHTML.trim();

                if (!title) {
                    alert('Please enter a feedback title');
                    return;
                }
                if (!recipientId) {
                    alert('Please select a recipient');
                    return;
                }
                if (content === '<p><br></p>' || !content) {
                    alert('Please enter feedback content');
                    return;
                }

                document.getElementById('confirmModal').style.display = 'block';
            }

            function hideConfirmModal() {
                document.getElementById('confirmModal').style.display = 'none';
            }

            // Modify mo ang submitForm function
            function submitForm() {
                let quillContent = quill.root.innerHTML;
                document.getElementById('feedback-content').value = quillContent;
                localStorage.setItem('feedbackJustSent', 'true');
                document.getElementById('feedbackForm').submit();
            }



            // Recipient search functionality
            document.getElementById('recipient').addEventListener('input', function() {
                let query = this.value;
                if (query.length > 0) {
                    fetch(`/search-users?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            let dropdown = document.getElementById('recipient-dropdown');
                            dropdown.innerHTML = '';
                            data.forEach(user => {
                                let item = document.createElement('div');
                                item.className = 'dropdown-item';
                                item.textContent = user.name;
                                item.dataset.userId = user.id;
                                item.addEventListener('click', function() {
                                    document.getElementById('recipient').value = user.name;
                                    document.getElementById('recipient-id').value = user.id;
                                    dropdown.style.display = 'none';
                                });
                                dropdown.appendChild(item);
                            });
                            dropdown.style.display = 'block';
                        });
                } else {
                    document.getElementById('recipient-dropdown').style.display = 'none';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.matches('#recipient')) {
                    document.getElementById('recipient-dropdown').style.display = 'none';
                }
            });

            // Close modal when clicking outside
            window.onclick = function(event) {
                let modal = document.getElementById('confirmModal');
                if (event.target == modal) {
                    hideConfirmModal();
                }
            }

            // Back button functionality
            document.getElementById('back').addEventListener('click', function() {
                window.history.back();
            });
        </script>
<script>
document.getElementById('feedback-diagnosis').addEventListener('change', function() {
    const otherDiagnosisDiv = document.getElementById('other-diagnosis-div');
    const otherDiagnosisInput = document.getElementById('other-diagnosis');
    const diagnosisSelect = document.getElementById('feedback-diagnosis');
    
    if (this.value === 'Others') {
        otherDiagnosisDiv.style.display = 'block';
        otherDiagnosisInput.required = true;
    } else {
        otherDiagnosisDiv.style.display = 'none';
        otherDiagnosisInput.required = false;
        otherDiagnosisInput.value = '';
    }
});

// Add a separate event listener for the other diagnosis input
document.getElementById('other-diagnosis').addEventListener('input', function() {
    const diagnosisSelect = document.getElementById('feedback-diagnosis');
    if (diagnosisSelect.value === 'Others' && this.value.trim() !== '') {
        // Update the select element's value directly
        const option = new Option(this.value, this.value, true, true);
        diagnosisSelect.add(option);
        diagnosisSelect.value = this.value;
    }
});

// Update the form submission to ensure the correct value is sent
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    const diagnosisSelect = document.getElementById('feedback-diagnosis');
    const otherDiagnosisInput = document.getElementById('other-diagnosis');
    
    if (diagnosisSelect.value === 'Others' && otherDiagnosisInput.value.trim() !== '') {
        diagnosisSelect.value = otherDiagnosisInput.value;
    }
});

</script>
    </body>
    </html>
</x-therapist-layout>
