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

        <style>
            /* The Modal (hidden by default) */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }
            .modal-content {
                display: block;
                background-color: white;
                margin: 15% auto;
                border-radius: 10px;
                width: 450px;
                text-align: center;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            .heads{
                width: 100%;
                background-color: #635c91;
                height: 15px;
                border-radius: 10px 10px 0 0;
            }

            .mod-cont{
                padding: 20px;
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

            .modal-buttons {
                display: flex;
                justify-content: flex-end;
                margin-top: 30px;
                border-top: 1px solid #afafaf;
                padding-top: 20px;
                gap: 20px;
            }

            .modal-btn {
                border: none;
                padding: 10px 20px;
                border-radius: 20px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .confirm-btn {
                background-color: #4F4A6E;
                color: white;
            }

            .confirm-btn:hover {
                background-color: #665f8d;
            }

            .cancel-btn {
                background-color: transparent;
                color: #395886;
                border: #395886 0.5px solid;
            }

            .cancel-btn:hover {
                background-color: #665f8d;
            }

            .relative-position {
                position: relative;
            }

            .dropdown-list {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
                background-color: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                display: none;
                z-index: 1000;
            }

            .dropdown-item {
                padding: 10px;
                cursor: pointer;
            }

            .dropdown-item:hover {
                background-color: #f5f5f5;
            }
        </style>
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
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
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

            function submitForm() {
                document.getElementById('feedback-content').value = quill.root.innerHTML;
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
    </body>
    </html>
</x-therapist-layout>
