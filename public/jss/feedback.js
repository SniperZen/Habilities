// Initialize the Quill editor with a custom toolbar and image paste/drop support
var toolbarOptions = [
    ['bold', 'italic', 'underline'],        // Text formatting options
    [{ 'align': [] }],                      // Text alignment options
    ['image']                               // Image upload button
];

var quill = new Quill('#editor', {
    theme: 'snow',                          // Use 'snow' theme
    modules: {
        toolbar: toolbarOptions,
        imageDropAndPaste: {}               // Enable the image paste and drop module
    }
});

// Optional: Fetch recipient list from an API
const apiUrl = 'https://api.example.com/recipients'; // Replace with your actual API

async function fetchRecipients() {
    try {
        const response = await fetch(apiUrl);
        const recipients = await response.json();
        const dataList = document.getElementById('recipient-list');
        
        recipients.forEach(recipient => {
            let option = document.createElement('option');
            option.value = recipient.name;
            dataList.appendChild(option);
        });
    } catch (error) {
        console.error('Error fetching recipients:', error);
    }
}

// Call the function on page load
document.addEventListener('DOMContentLoaded', fetchRecipients);

// Handle form submission
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get the content from the Quill editor, including pasted images
    const feedbackContent = quill.root.innerHTML;
    
    const feedbackData = {
        title: document.getElementById('feedback-title').value,
        recipient: document.getElementById('recipient').value,
        content: feedbackContent
    };

    console.log('Feedback Data:', feedbackData);
    
    // You can send feedbackData to your API here using fetch or axios
});
