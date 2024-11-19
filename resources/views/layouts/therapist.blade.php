<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        [x-cloak]{
                display: none !important;
            }
    </style>
  </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/therapist.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('jss/modal.js')}}"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

</head>
<body x-data="{ openSidebar: false, openUsers: false }" :class="{'no-scroll': openSidebar}">

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
                <svg class="arrowd" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </div>
                <div x-show="open" @click.away="open = false" class="dropdown" x-cloak>
                    <a href="{{route('therapist.profile')}}" class="dropdown-item">
                    <svg width="23" height="23" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:3px;">
                        <path d="M3 25.6405C3 20.9211 6.94286 17.0952 15 17.0952C23.0571 17.0952 27 20.9211 27 25.6405C27 26.3913 26.4522 27 25.7765 27H4.22353C3.54779 27 3 26.3913 3 25.6405Z" stroke="black" stroke-width="3"/>
                        <path d="M19.5 7.5C19.5 9.98528 17.4853 12 15 12C12.5147 12 10.5 9.98528 10.5 7.5C10.5 5.01472 12.5147 3 15 3C17.4853 3 19.5 5.01472 19.5 7.5Z" stroke="black" stroke-width="3"/>
                    </svg>Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="handleLogout(event)">
                            <svg width="23" height="23" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:3px;">
                                <path d="M21.3603 11.375V8.3125C21.3603 7.50027 21.0349 6.72132 20.4558 6.14699C19.8766 5.57266 19.0911 5.25 18.2721 5.25H7.46324C6.64418 5.25 5.85868 5.57266 5.27952 6.14699C4.70037 6.72132 4.375 7.50027 4.375 8.3125V26.6875C4.375 27.4997 4.70037 28.2787 5.27952 28.853C5.85868 29.4273 6.64418 29.75 7.46324 29.75H18.2721C19.0911 29.75 19.8766 29.4273 20.4558 28.853C21.0349 28.2787 21.3603 27.4997 21.3603 26.6875V23.625M12.0956 17.5H30.625M30.625 17.5L25.9926 12.9062M30.625 17.5L25.9926 22.0938" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>Log Out
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Sidebar and other content -->
    <div class="Therapist-container">
        <aside class="sidebarr" :class="{'hidden': !openSidebar}">
        <div><<svg @click="openSidebar = false" class="close-sidebar" class="logos" width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.02771 16.0466L27.0277 16.0085" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.01392 8.04663L27.0139 8.00848" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.04163 24.0466L27.0416 24.0085" stroke="#395886" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <nav>
                    <ul>
                        <li>
                            <x-side-nav-bar href="{{ route('therapist.dash') }}" class="{{ request()->routeIs('therapist.dash') ? 'active' : '' }}">
                            <svg width="40" height="40" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.6524 16.1321L28.2344 13.7447V7.89391C28.2344 6.98771 27.4841 6.24627 26.5669 6.24627H24.8994C23.9823 6.24627 23.2319 6.98771 23.2319 7.89391V8.80505L19.8968 5.51307C19.4416 5.08798 19.0247 4.59863 18.2293 4.59863C17.4339 4.59863 17.017 5.08798 16.5618 5.51307L5.8063 16.1321C5.28603 16.6676 4.88916 17.0581 4.88916 17.7797C4.88916 18.7074 5.60953 19.4274 6.55668 19.4274H8.2242V29.3132C8.2242 30.2194 8.97459 30.9608 9.89172 30.9608H13.2268C14.1477 30.9608 14.8943 30.2232 14.8943 29.3132V22.7227C14.8943 21.8165 15.6447 21.075 16.5618 21.075H19.8968C20.814 21.075 21.5644 21.8165 21.5644 22.7227V29.3132C21.5644 30.2232 21.4772 30.9608 22.3981 30.9608H26.5669C27.4841 30.9608 28.2344 30.2194 28.2344 29.3132V19.4274H29.902C30.8491 19.4274 31.5695 18.7074 31.5695 17.7797C31.5695 17.0581 31.1726 16.6676 30.6524 16.1321Z" stroke="#303B1D" stroke-width="3" stroke-linejoin="round"/>
                            </svg>Dashboard
                            </x-side-nav-bar>
                        </li>
                        <li>
                            <x-side-nav-bar href="{{ route('therapist.AppReq') }}" class="{{ request()->routeIs('therapist.AppReq') || request()->routeIs('therapist.AppReq2') || request()->routeIs('therapist.AppSched')  ? 'active' : '' }}">
                            <svg width="40" height="40" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.48322 11.817H28.2346M10.1654 4.26562V6.23578M25.2701 4.26562V6.23554M29.7168 9.68291V23.8007C29.7168 25.7046 27.9472 27.2481 25.7642 27.2481H9.95362C7.77063 27.2481 6.00098 25.7046 6.00098 23.8007V9.68291C6.00098 7.77898 7.77063 6.23554 9.95362 6.23554H25.7642C27.9472 6.23554 29.7168 7.77898 29.7168 9.68291Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>Appointment
                            </x-side-nav-bar>
                        </li>
                        <li>
                            <x-side-nav-bar href="{{ route('therapist.inquiry') }}" class="{{ request()->routeIs('therapist.inquiry') || request()->routeIs('inquiry.message')   ? 'active' : '' }}">
                            <svg width="37" height="37" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4 21.6001H5.39999C4.0745 21.6001 2.99999 20.5256 3 19.2001L3.00009 4.80013C3.0001 3.47466 4.07462 2.40015 5.40009 2.40015H16.2004C17.5258 2.40015 18.6004 3.47466 18.6004 4.80015V9.60015M19.8 19.8001L21 21.0001M7.20037 7.20015H14.4004M7.20037 10.8001H14.4004M7.20037 14.4001H10.8004M20.4 17.4001C20.4 19.057 19.0569 20.4001 17.4 20.4001C15.7431 20.4001 14.4 19.057 14.4 17.4001C14.4 15.7433 15.7431 14.4001 17.4 14.4001C19.0569 14.4001 20.4 15.7433 20.4 17.4001Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>Inquiry
                            </x-side-nav-bar>
                        </li>
                        <li>
                            <x-side-nav-bar href="{{ route('therapist.feedback') }}" class="{{ request()->routeIs('therapist.feedback') ||request()->routeIs('therapist.feedback3') || request()->routeIs('therapist.feedback2') ? 'active' : '' }}">
                            <svg width="40" height="40" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.2338 3.51465V8.78709C22.2338 9.75772 23.0302 10.5446 24.0125 10.5446H29.3486M12.4511 10.5446H16.0084M12.4511 15.817H23.1232M12.4511 21.0895H23.1232M26.6806 6.15087C25.8889 5.45101 25.0675 4.62095 24.5489 4.08184C24.2038 3.72311 23.727 3.51465 23.2261 3.51465H9.78261C7.81794 3.51465 6.22525 5.08834 6.22524 7.02959L6.2251 28.1193C6.22509 30.0605 7.81777 31.6343 9.78246 31.6343L25.7907 31.6343C27.7554 31.6343 29.3481 30.0607 29.3481 28.1195L29.3486 9.48691C29.3486 9.03752 29.1751 8.60554 28.8594 8.28201C28.2757 7.68375 27.301 6.69938 26.6806 6.15087Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>Therapist Feedback
                            </x-side-nav-bar>
                        </li>
                        <li>
                            <x-side-nav-bar href="{{ route('therapist.myHistory') }}" class="{{ request()->routeIs('therapist.myHistory')? 'active' : '' }}">
                            <svg style="margin-right: 3px;" width="33" height="33" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.0714 17.5L16.5021 16.9307C16.1806 16.6092 16 16.1731 16 15.7185V14M6 20H5C3.89543 20 3 19.1046 3 18V10M21 7V6.5C21 5.39543 20.1046 4.5 19 4.5H18M3 10H6M3 10V6.5C3 5.39543 3.89543 4.5 5 4.5H6M18 3V4.5M18 4.5H12M12 3V4.5M12 4.5H6M6 3V4.5M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>Appointment History
                            </x-side-nav-bar>
                        </li>
                        <li>
                        <x-side-nav-bar href="{{ route('therapist.chat', ['id' => Auth::id()]) }}" class="{{ request()->routeIs('therapist.chat') ? 'active' : '' }}">
                            <svg width="40" height="40" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M27.7083 15.3122V8.74967C27.7083 7.13884 26.4025 5.83301 24.7917 5.83301H7.29167C5.68084 5.83301 4.375 7.13885 4.375 8.74968V20.1627C4.375 21.7735 5.68084 23.0794 7.29167 23.0794H9.57428V29.1663L15.6612 23.0794H16.0417M23.5711 26.8203L27.3755 30.6247V26.8203H27.7083C29.3192 26.8203 30.625 25.5145 30.625 23.9037V18.958C30.625 17.3472 29.3192 16.0413 27.7083 16.0413H18.9583C17.3475 16.0413 16.0417 17.3472 16.0417 18.958V23.9037C16.0417 25.5145 17.3475 26.8203 18.9583 26.8203H23.5711Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>Chats
                            </x-side-nav-bar>
                        </li>
                    </ul> 
                </nav>
                </div>
                <div class="footer-links">
                    <a href="{{route('therapist.t-about')}}">About Habilities</a>
                    <a href="{{route('therapist.t-help')}}">Help Center</a>
                    <a href="{{route('therapist.t-feedback')}}">Feedback</a>
                </div>
            </aside>
            <main class="main"> 
                {{$slot}}
            </main>
    </div>

    <script>    
        function handleLogout(event) {
            event.preventDefault();
            localStorage.removeItem('selectedPage');
            event.target.closest('form').submit();
        }
    </script>
</body>
</html>
