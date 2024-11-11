<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/therapist/help.css')}}">
    <title>Help Center</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const arrow = item.querySelector('.arrow');

        question.addEventListener('click', () => {
            const isOpen = answer.style.display === 'block';
            
            // Hide all answers and reset arrows
            faqItems.forEach(i => {
                i.querySelector('.faq-answer').style.display = 'none';
                i.querySelector('.arrow').classList.remove('up');
            });

            // Toggle the clicked answer and arrow
            if (!isOpen) {
                answer.style.display = 'block';
                arrow.classList.add('up');
            }
        });
    });
});

    </script>
</head>
<body>

<div class="faq-container">
    <h1>Help Center</h1>
    <p>Frequently Asked Questions</p>

    <div class="faq-item">
        <button class="faq-question">
            Where is your center located?
            <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
            <p>We are located at 112 Sampaguita St. phase 1, Alido Subd. Brgy. Bulihan, City of Malolos, Bulacan</p>
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            How much does your service cost?
            <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
            <p> Occupational Therapy - ₱650 <br>  
                Speech Therapy - ₱800 <br>
                Behavioral Therapy - * <br>
                Sped Intervention - ₱500 <br><br>
                *- Subject to changes without prior notice as per Tx advise.
</p>
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            Do you have open slots?
            <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
            <p>Tuesday, Wednesday, Thursday, and Saturday - 10:00 AM  to 4:00 PM. </p>
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            What are your center's hours?
            <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
            <p>Friday - 12:00 PM - 3:00 PM.</p>
        </div>
    </div>
</div>

<script src="faq.js"></script>

</body>
</html>
</x-therapist-layout>