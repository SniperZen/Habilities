<x-therapist-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist.css') }}">
    <link rel="icon" href="images/logo.png" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('jss/modal.js')}}"></script>
</head>
<body x-data="{ openUsers: false }">

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.png') }}" alt="Habilities Center Logo" class="logo">
            <h1>Habilities Center for Intervention</h1>
        </div>
        <div class="header-right" id="headerRight" x-data="{ open: false }">
            <div class="profile">
                <div class="prof_box" @click="open = !open" style="gap:15px">
                @if(Auth::user()->profile_image)
                    <div style="width: 35px; height: 35px; overflow: hidden; border-radius: 50%; ">
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                             alt="Profile Picture" 
                             style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                    </div>
                @else
                    <p>No profile image set</p>
                @endif
                <p><u>{{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name ? $user->middle_name[0] . '.' : '' }}</u></p>
                </div>
                <div x-show="open" @click.away="open = false" class="dropdown">
                    <a href="#" data-page="{{ route('patient.profile') }}" onclick="switchPage('{{ route('therapist.profile') }}')" class="dropdown-item">
                        <img src="{{ asset('images/nav/profile.png') }}" alt="">Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="handleLogout(event)">
                            <img src="{{ asset('images/nav/logout.png') }}" alt="">Log Out
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Iframe -->
    <iframe id="pageFrame"></iframe>

    <!-- Sidebar and other content -->
    <div class="container">
        <aside class="sidebarr">
            <div>
            <nav>
                <ul>
                    <li>
                        <a href="#" data-page="{{ route('therapist.dash') }}" onclick="switchPage('{{ route('therapist.dash') }}')">
                            <img src="{{ asset('images/nav/home.png') }}" alt="">Home
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="{{ route('therapist.AppReq')}}" onclick="switchPage('{{ route('therapist.AppReq')}}')">
                            <img src="{{ asset('images/nav/appointment.png') }}" alt="">Appointment
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="{{ route('therapist.inquiry')}}" onclick="switchPage('{{ route('therapist.inquiry')}}')">
                            <img src="{{ asset('images/nav/inquiry.png') }}" alt="">Inquiry
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="{{ route('therapist.feedback') }}" onclick="switchPage('{{ route('therapist.feedback') }}')">
                            <img src="{{ asset('images/nav/tf.png') }}" alt="">Therapist Feedback
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="{{route('therapist.chat')}}" onclick="switchPage('{{route('therapist.chat')}}')">
                            <img src="{{ asset('images/nav/chat.png') }}" alt="">Chats
                        </a>
                    </li>
                </ul> 
            </nav>
            </div>
            <div class="footer-links">
                <a href="#">About Habilities</a>
                <a href="#">Contact US</a>
                <a href="#">Report a problem</a>
            </div>
        </aside>

        <div id="appointmentModal" class="modals">
        </div>
    </div>

    <script>
        function switchPage(pageName) {
            document.getElementById('pageFrame').src = pageName;
            localStorage.setItem('selectedPage', pageName);
            updateActiveNav(pageName);
        }

        function updateActiveNav(pageName) {
            const navLinks = document.querySelectorAll('nav a[data-page]');
            navLinks.forEach(link => link.classList.remove('active'));
            
            const activeLink = Array.from(navLinks).find(link => link.getAttribute('data-page') === pageName);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }

        window.onload = function() {
            const selectedPage = localStorage.getItem('selectedPage') || '{{ route('therapist.dash') }}';
            document.getElementById('pageFrame').src = selectedPage;
            updateActiveNav(selectedPage);
        }

        // Hide header-right on iframe click
        document.getElementById('pageFrame').addEventListener('click', function() {
            document.getElementById('headerRight').style.display = 'none';
        });

        // Show header-right on any click outside the iframe
        document.body.addEventListener('click', function(event) {
            if (event.target.tagName !== 'IFRAME') {
                document.getElementById('headerRight').style.display = 'flex'; // Show header-right again
            }
        }, true);

        function handleLogout(event) {
            event.preventDefault();
            localStorage.removeItem('selectedPage');
            event.target.closest('form').submit();
        }
    </script>
</body>
</html>
    </x-therapist-layout>