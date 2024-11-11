<x-patient-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Request</title>
    <link rel="stylesheet" href="{{ asset('css/patient/ReqApp.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
   
</head>
<body>
    <container>
    <main class="main-content">
        <section class="appointment-request">
            <h2>Request an appointment</h2>
            <p class="p">Book a therapy session at Habilities with just easy steps! <br> Please select the therapist you’d like to request an appointment with.</p>
            

            <div class="inside-content">
                <div class="progress-bar">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step inactive">
                        <div class="step-number">2</div>
                        <div class="step-line"></div>
                    </div>
                </div>
            
                <div class="line"></div>
                    <div class="scroller">
                        <div class="therapist-cards">
                            @foreach($therapists as $therapist)
                            <div class="therapist-card">
                                <img src="{{ $therapist->profile_image ? asset('storage/' . $therapist->profile_image) : 'https://via.placeholder.com/80' }}" alt="Therapist">
                                <div>
                                    <h3>{{ $therapist->first_name }} {{ $therapist->last_name }}</h3>
                                    <p>{{ $therapist->specialization ?? 'Specialization not specified' }}</p>
                                    <div class="availability">
                                        <i class="icon-calendar"></i> 
                                        {{ str_replace(',', ', ', $therapist->availability ?? 'Availability not specified') }}
                                    </div>
                                    <a href="{{ route('patient.CompApp', ['id' => $therapist->id]) }}">
                                        <button class="appointment-button">Request an appointment</button>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        </div>
                        <div class="buts"><a href="{{route('patient.appntmnt')}}"><button class="but">Back</button></a></div>
                    </div>
            </div>
        </section>
    </main>
</container>
</body>
</html>
</x-patient-layout>