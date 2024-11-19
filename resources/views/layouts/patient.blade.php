<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        [x-cloak]{
                display: none !important;
            }

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/patient/dash.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="{{ asset('jss/modal.js')}}"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
</head>
<body x-data="{ openSidebar: false, openUsers: false }" :class="{'no-scroll': openSidebar}">
    <div class="header">
        <div class="header-left">
            <svg @click="openSidebar = !openSidebar" class="hamburger"  class="logos" width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.02771 16.0466L27.0277 16.0085" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.01392 8.04663L27.0139 8.00848" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.04163 24.0466L27.0416 24.0085" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <img src="{{ asset('images/logo.png') }}" alt="Habilities Center Logo" class="logo">
            <h1>Habilities Center for Intervention</h1>
        </div>
        <div class="header-right" x-data="{ open: false }">
            <div class="profile">
            <div class="prof_box" @click="open = !open" style="gap:7px">
                @if(Auth::user()->profile_image)
                    <div class="prof">
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                            alt="Profile Picture" >
                    </div>
                @else
                    <p>No profile image set</p>
                @endif
                <p><u>{{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name ? $user->middle_name[0] . '.' : '' }}</u></p>
                <svg class="arrowd" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </div>
                <div x-show="open" @click.away="open = false" class="dropdown" x-cloak>
                    <a href="{{ route('patient.profile') }}" class="dropdown-item">
                        <img src="{{ asset('images/nav/profile.png') }}" alt="">Profile
                    </a>
                    <a href="{{ route('patient.myHistory') }}" class="dropdown-item">
                        <img src="{{ asset('images/nav/history.png') }}" alt="">My History
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                        @csrf
                    </form>

                    <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img src="{{ asset('images/nav/logout.png') }}" alt="">Log Out
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
    <aside class="sidebar" :class="{'hidden': !openSidebar}">
    <div><<svg @click="openSidebar = false" class="close-sidebar" class="logos" width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.02771 16.0466L27.0277 16.0085" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.01392 8.04663L27.0139 8.00848" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.04163 24.0466L27.0416 24.0085" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <nav>
            <ul>
                <li>
                    <x-side-nav-bar href="{{ route('patient.dash') }}" class="{{ request()->routeIs('patient.dash') ? 'active' : '' }}">
                    <svg width="37" height="37" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M30.6524 16.1321L28.2344 13.7447V7.89391C28.2344 6.98771 27.4841 6.24627 26.5669 6.24627H24.8994C23.9823 6.24627 23.2319 6.98771 23.2319 7.89391V8.80505L19.8968 5.51307C19.4416 5.08798 19.0247 4.59863 18.2293 4.59863C17.4339 4.59863 17.017 5.08798 16.5618 5.51307L5.8063 16.1321C5.28603 16.6676 4.88916 17.0581 4.88916 17.7797C4.88916 18.7074 5.60953 19.4274 6.55668 19.4274H8.2242V29.3132C8.2242 30.2194 8.97459 30.9608 9.89172 30.9608H13.2268C14.1477 30.9608 14.8943 30.2232 14.8943 29.3132V22.7227C14.8943 21.8165 15.6447 21.075 16.5618 21.075H19.8968C20.814 21.075 21.5644 21.8165 21.5644 22.7227V29.3132C21.5644 30.2232 21.4772 30.9608 22.3981 30.9608H26.5669C27.4841 30.9608 28.2344 30.2194 28.2344 29.3132V19.4274H29.902C30.8491 19.4274 31.5695 18.7074 31.5695 17.7797C31.5695 17.0581 31.1726 16.6676 30.6524 16.1321Z" stroke="#222222" stroke-width="3" stroke-linejoin="round"/>
                    </svg>Home
                    </x-side-nav-bar>
                </li>
                <li>
                    <x-side-nav-bar href="{{ route('patient.inquiry01') }}" class="{{ request()->routeIs('patient.inquiry01')|| request()->routeIs('patient.inquiry') || request()->routeIs('patient.inquiry2') || request()->routeIs('patient.inquiry3')? 'active' : '' }}">
                    <svg width="37" height="37" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.4 21.6001H5.39999C4.0745 21.6001 2.99999 20.5256 3 19.2001L3.00009 4.80013C3.0001 3.47466 4.07462 2.40015 5.40009 2.40015H16.2004C17.5258 2.40015 18.6004 3.47466 18.6004 4.80015V9.60015M19.8 19.8001L21 21.0001M7.20037 7.20015H14.4004M7.20037 10.8001H14.4004M7.20037 14.4001H10.8004M20.4 17.4001C20.4 19.057 19.0569 20.4001 17.4 20.4001C15.7431 20.4001 14.4 19.057 14.4 17.4001C14.4 15.7433 15.7431 14.4001 17.4 14.4001C19.0569 14.4001 20.4 15.7433 20.4 17.4001Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>Inquiry
                    </x-side-nav-bar>
                </li>
                <li>
                    <x-side-nav-bar href="{{ route('patient.appntmnt') }}" class="{{ request()->routeIs('patient.appntmnt') || request()->routeIs('patient.AppReq')|| request()->routeIs('patient.CompApp')  ? 'active' : '' }}">
                    <svg width="37" height="37" viewBox="0 0 36 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.04083 11.3814H27.7922M9.72298 3.83008V5.80023M24.8277 3.83008V5.79999M29.2744 9.24736V23.3652C29.2744 25.2691 27.5048 26.8125 25.3218 26.8125H9.51123C7.32825 26.8125 5.55859 25.2691 5.55859 23.3652V9.24736C5.55859 7.34343 7.32825 5.79999 9.51124 5.79999H25.3218C27.5048 5.79999 29.2744 7.34343 29.2744 9.24736Z" stroke="#222222" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>Appointment
                    </x-side-nav-bar>
                </li>
                <li>
                    <x-side-nav-bar href="{{ route('patient.myHistory') }}" class="{{ request()->routeIs('patient.myHistory')? 'active' : '' }}">
                    <svg style="margin-right: 3px;" width="37" height="37" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.0714 17.5L16.5021 16.9307C16.1806 16.6092 16 16.1731 16 15.7185V14M6 20H5C3.89543 20 3 19.1046 3 18V10M21 7V6.5C21 5.39543 20.1046 4.5 19 4.5H18M3 10H6M3 10V6.5C3 5.39543 3.89543 4.5 5 4.5H6M18 3V4.5M18 4.5H12M12 3V4.5M12 4.5H6M6 3V4.5M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>My History
                    </x-side-nav-bar>
                </li>
                <li>
                    <x-side-nav-bar href="{{ route('patient.chat') }}" class="{{ request()->routeIs('patient.chat') ? 'active' : '' }}">
                    <svg width="37" height="37" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27.7083 15.3122V8.74967C27.7083 7.13884 26.4025 5.83301 24.7917 5.83301H7.29167C5.68084 5.83301 4.375 7.13885 4.375 8.74968V20.1627C4.375 21.7735 5.68084 23.0794 7.29167 23.0794H9.57428V29.1663L15.6612 23.0794H16.0417M23.5711 26.8203L27.3755 30.6247V26.8203H27.7083C29.3192 26.8203 30.625 25.5145 30.625 23.9037V18.958C30.625 17.3472 29.3192 16.0413 27.7083 16.0413H18.9583C17.3475 16.0413 16.0417 17.3472 16.0417 18.958V23.9037C16.0417 25.5145 17.3475 26.8203 18.9583 26.8203H23.5711Z" stroke="#222222" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>Chats
                    </x-side-nav-bar>
                </li>
            </ul> 
        </nav>
        </div>
        <div class="footer-links">
            <a href="{{route('patient.p-about')}}">About Habilities</a>
            <a href="{{route('patient.p-help')}}">Help Center</a>
            <a href="{{route('patient.p-feedback')}}">Feedback</a>
        </div>
    </aside>


        <main class="main"> 
            {{$slot}}
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggleSidebar');

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1080) {
                sidebar.classList.remove('hidden'); 
            } else {
                sidebar.classList.add('hidden'); 
            }
        });

        if (window.innerWidth >= 1080) {
            sidebar.classList.remove('hidden');
        }
        function handleLogout(event) {
                    event.preventDefault();
                    localStorage.removeItem('selectedPage');
                    event.target.closest('form').submit();
                }
    </script>
</body>
</html>
