<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('jss/modal.js')}}"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        .hidden {
            display: none;
        }
        .submenu, .submenu2 {
            list-style: none;
            padding-left: 20px;
            transition: max-height 0.3s ease;
            overflow: hidden; 
        }
        .submenu a {
            display: flex;
        }
        .active {
            font-weight: bold; 
            color: #007bff; 
        }
        [x-cloak]{
            display: none !important;
        }
    </style>

  </script>
</head>
<body x-data="{ openUsers: false, activeSubmenu: '' }" class="fade-in">
    <div class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.png') }}" alt="Habilities Center Logo" class="logo">
            <h1>Habilities Center for Intervention</h1>
        </div>
        <div class="header-right" x-data="{ open: false }">
            <div class="profile" @click="open = !open">
                <!--<input class="search-input" type="text" placeholder="Search for user...">-->
                <div class="profile-pic"><img src="{{ asset('images/others/default-prof.png') }}" alt="Profile Picture"></div>
                <p><u>Administrator</u></p>
                <svg class="arrowd" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                <div x-show="open" @click.away="open = false" class="dropdown" x-cloak>
                    <!-- <a href="{{ route('patient.profile') }}" class="dropdown-item">Profile</a> -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="handleLogout(event)">Log Out</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li>
                        <x-side-nav-bar href="{{ route('admin.dash') }}" class="{{ request()->routeIs('admin.dash') ? 'active' : '' }}">
                            <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.6524 16.1323L28.2344 13.7449V7.89415C28.2344 6.98795 27.4841 6.24652 26.5669 6.24652H24.8994C23.9823 6.24652 23.2319 6.98795 23.2319 7.89415V8.8053L19.8968 5.51332C19.4416 5.08823 19.0247 4.59888 18.2293 4.59888C17.4339 4.59888 17.017 5.08823 16.5618 5.51332L5.8063 16.1323C5.28603 16.6678 4.88916 17.0583 4.88916 17.78C4.88916 18.7076 5.60953 19.4276 6.55668 19.4276H8.2242V29.3135C8.2242 30.2197 8.97459 30.9611 9.89172 30.9611H13.2268C14.1477 30.9611 14.8943 30.2234 14.8943 29.3135V22.7229C14.8943 21.8167 15.6447 21.0753 16.5618 21.0753H19.8968C20.814 21.0753 21.5644 21.8167 21.5644 22.7229V29.3135C21.5644 30.2234 21.4772 30.9611 22.3981 30.9611H26.5669C27.4841 30.9611 28.2344 30.2197 28.2344 29.3135V19.4276H29.902C30.8491 19.4276 31.5695 18.7076 31.5695 17.78C31.5695 17.0583 31.1726 16.6678 30.6524 16.1323Z" stroke="#303B1D" stroke-width="3" stroke-linejoin="round"/>
                            </svg>Dashboard
                        </x-side-nav-bar>
                    </li>
                    <li>
                        <a href="#" class="toggle-users" onclick="toggleSubmenu(event)">
                            <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M26.3998 18.8179C28.1424 20.1199 29.6998 23.398 29.6998 25.4178C29.6998 26.047 29.2407 26.5572 28.6744 26.5572H28.0498M21.4498 13.4812C22.5771 12.8291 23.3355 11.6103 23.3355 10.2143C23.3355 8.81834 22.5771 7.59952 21.4498 6.94742M4.32524 26.5572H22.3887C22.955 26.5572 23.4141 26.047 23.4141 25.4178C23.4141 21.4625 20.1096 18.256 13.3569 18.256C6.60429 18.256 3.2998 21.4625 3.2998 25.4178C3.2998 26.047 3.75891 26.5572 4.32524 26.5572ZM17.1284 10.2143C17.1284 12.2972 15.4398 13.9857 13.3569 13.9857C11.274 13.9857 9.58552 12.2972 9.58552 10.2143C9.58552 8.1314 11.274 6.44287 13.3569 6.44287C15.4398 6.44287 17.1284 8.1314 17.1284 10.2143Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round"/>
                            </svg>Users <span class="arrow">▶</span>
                        </a>
                        <ul class="submenu" id="user-submenu">
                            <li >
                                <x-side-nav-bar href="{{ route('admin.usersTherapist') }}" 
                                                class="{{ request()->routeIs('admin.usersTherapist') ? 'active' : '' }} subnav">
                                    <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M26.3998 18.8179C28.1424 20.1199 29.6998 23.398 29.6998 25.4178C29.6998 26.047 29.2407 26.5572 28.6744 26.5572H28.0498M21.4498 13.4812C22.5771 12.8291 23.3355 11.6103 23.3355 10.2143C23.3355 8.81834 22.5771 7.59952 21.4498 6.94742M4.32524 26.5572H22.3887C22.955 26.5572 23.4141 26.047 23.4141 25.4178C23.4141 21.4625 20.1096 18.256 13.3569 18.256C6.60429 18.256 3.2998 21.4625 3.2998 25.4178C3.2998 26.047 3.75891 26.5572 4.32524 26.5572ZM17.1284 10.2143C17.1284 12.2972 15.4398 13.9857 13.3569 13.9857C11.274 13.9857 9.58552 12.2972 9.58552 10.2143C9.58552 8.1314 11.274 6.44287 13.3569 6.44287C15.4398 6.44287 17.1284 8.1314 17.1284 10.2143Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round"/>
                                    </svg>Therapists
                                </x-side-nav-bar>
                            </li>
                            <li>
                                <x-side-nav-bar href="{{ route('admin.usersPatient') }}" 
                                                class="{{ request()->routeIs('admin.usersPatient') ? 'active' : '' }} subnav">
                                    <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M26.3998 18.8179C28.1424 20.1199 29.6998 23.398 29.6998 25.4178C29.6998 26.047 29.2407 26.5572 28.6744 26.5572H28.0498M21.4498 13.4812C22.5771 12.8291 23.3355 11.6103 23.3355 10.2143C23.3355 8.81834 22.5771 7.59952 21.4498 6.94742M4.32524 26.5572H22.3887C22.955 26.5572 23.4141 26.047 23.4141 25.4178C23.4141 21.4625 20.1096 18.256 13.3569 18.256C6.60429 18.256 3.2998 21.4625 3.2998 25.4178C3.2998 26.047 3.75891 26.5572 4.32524 26.5572ZM17.1284 10.2143C17.1284 12.2972 15.4398 13.9857 13.3569 13.9857C11.274 13.9857 9.58552 12.2972 9.58552 10.2143C9.58552 8.1314 11.274 6.44287 13.3569 6.44287C15.4398 6.44287 17.1284 8.1314 17.1284 10.2143Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round"/>
                                    </svg>Patients
                                </x-side-nav-bar>
                        </li>
                    </ul>
                    <li>
                            <x-side-nav-bar href="{{ route('admin.therapycenter') }}" 
                                class="{{ request()->routeIs('admin.therapycenter') || request()->routeIs('admin.editTCenter') ? 'active' : '' }}">
                                <svg width="15%" height="auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.4 21.6001H5.39999C4.0745 21.6001 2.99999 20.5256 3 19.2001L3.00009 4.80013C3.0001 3.47466 4.07462 2.40015 5.40009 2.40015H16.2004C17.5258 2.40015 18.6004 3.47466 18.6004 4.80015V9.60015M19.8 19.8001L21 21.0001M7.20037 7.20015H14.4004M7.20037 10.8001H14.4004M7.20037 14.4001H10.8004M20.4 17.4001C20.4 19.057 19.0569 20.4001 17.4 20.4001C15.7431 20.4001 14.4 19.057 14.4 17.4001C14.4 15.7433 15.7431 14.4001 17.4 14.4001C19.0569 14.4001 20.4 15.7433 20.4 17.4001Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>Therapy Center
                            </x-side-nav-bar>
                        </li>
                <li>
                <x-side-nav-bar href="{{ route('admin.chat', ['id' => Auth::id()]) }}"  class="{{ request()->routeIs('admin.chat') ? 'active' : '' }}">                    
                    <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27.7083 15.3124V8.74992C27.7083 7.13909 26.4025 5.83325 24.7917 5.83325H7.29167C5.68084 5.83325 4.375 7.13909 4.375 8.74992V20.163C4.375 21.7738 5.68084 23.0796 7.29167 23.0796H9.57428V29.1666L15.6612 23.0796H16.0417M23.5711 26.8206L27.3755 30.6249V26.8206H27.7083C29.3192 26.8206 30.625 25.5147 30.625 23.9039V18.9582C30.625 17.3474 29.3192 16.0416 27.7083 16.0416H18.9583C17.3475 16.0416 16.0417 17.3474 16.0417 18.9583V23.9039C16.0417 25.5147 17.3475 26.8206 18.9583 26.8206H23.5711Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>Chats
                    </x-side-nav-bar>
                </li>
                <!-- <li>
                    <x-side-nav-bar href="{{ route('admin.report') }}" class="{{ request()->routeIs('admin.report') ? 'active' : '' }}">
                    <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.2001 15.3828L15.6751 17.8578L20.6251 12.9078M6.6001 7.95777L14.2864 4.11463C15.68 3.41785 17.3202 3.41785 18.7138 4.11463L26.4001 7.95777C26.4001 7.95777 26.4001 15.1848 26.4001 18.9963C26.4001 22.8078 22.8768 25.3807 16.5001 29.4078C10.1234 25.3807 6.6001 21.9828 6.6001 18.9963V7.95777Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>Reports
                    </x-side-nav-bar>
                </li> -->

                <li>
                        <a href="#" class="toggle-users2" onclick="toggleSubmenu2(event)">
                        <svg width="15%" height="auto" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.2001 15.3828L15.6751 17.8578L20.6251 12.9078M6.6001 7.95777L14.2864 4.11463C15.68 3.41785 17.3202 3.41785 18.7138 4.11463L26.4001 7.95777C26.4001 7.95777 26.4001 15.1848 26.4001 18.9963C26.4001 22.8078 22.8768 25.3807 16.5001 29.4078C10.1234 25.3807 6.6001 21.9828 6.6001 18.9963V7.95777Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>Reports <span class="arrow2">▶</span>
                        </a>
                        <ul class="submenu2" id="user-submenu2">
                            <li >
                                <x-side-nav-bar href="{{ route('admin.appointmentr') }}" 
                                                class="{{ request()->routeIs('admin.appointmentr') ? 'active' : '' }} subnav2">
                                        <svg width="40" height="40" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.48322 11.817H28.2346M10.1654 4.26562V6.23578M25.2701 4.26562V6.23554M29.7168 9.68291V23.8007C29.7168 25.7046 27.9472 27.2481 25.7642 27.2481H9.95362C7.77063 27.2481 6.00098 25.7046 6.00098 23.8007V9.68291C6.00098 7.77898 7.77063 6.23554 9.95362 6.23554H25.7642C27.9472 6.23554 29.7168 7.77898 29.7168 9.68291Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>Apoointment Reports
                                </x-side-nav-bar>
                            </li>
                            <li>
                                <x-side-nav-bar href="{{ route('admin.inquiryr') }}" 
                                                class="{{ request()->routeIs('admin.inquiryr') ? 'active' : '' }} subnav2">
                                    <svg width="37" height="37" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.4 21.6001H5.39999C4.0745 21.6001 2.99999 20.5256 3 19.2001L3.00009 4.80013C3.0001 3.47466 4.07462 2.40015 5.40009 2.40015H16.2004C17.5258 2.40015 18.6004 3.47466 18.6004 4.80015V9.60015M19.8 19.8001L21 21.0001M7.20037 7.20015H14.4004M7.20037 10.8001H14.4004M7.20037 14.4001H10.8004M20.4 17.4001C20.4 19.057 19.0569 20.4001 17.4 20.4001C15.7431 20.4001 14.4 19.057 14.4 17.4001C14.4 15.7433 15.7431 14.4001 17.4 14.4001C19.0569 14.4001 20.4 15.7433 20.4 17.4001Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>Inquiry Reports
                                </x-side-nav-bar>
                            </li>
                            <li>
                                <x-side-nav-bar href="{{ route('admin.otfr') }}" 
                                                class="{{ request()->routeIs('admin.otfr') ? 'active' : '' }} subnav2">
                                    <svg width="40" height="40" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22.2338 3.51465V8.78709C22.2338 9.75772 23.0302 10.5446 24.0125 10.5446H29.3486M12.4511 10.5446H16.0084M12.4511 15.817H23.1232M12.4511 21.0895H23.1232M26.6806 6.15087C25.8889 5.45101 25.0675 4.62095 24.5489 4.08184C24.2038 3.72311 23.727 3.51465 23.2261 3.51465H9.78261C7.81794 3.51465 6.22525 5.08834 6.22524 7.02959L6.2251 28.1193C6.22509 30.0605 7.81777 31.6343 9.78246 31.6343L25.7907 31.6343C27.7554 31.6343 29.3481 30.0607 29.3481 28.1195L29.3486 9.48691C29.3486 9.03752 29.1751 8.60554 28.8594 8.28201C28.2757 7.68375 27.301 6.69938 26.6806 6.15087Z" stroke="#303B1D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>Feedback Reports
                                </x-side-nav-bar>
                            </li>
                            <li>
                                <x-side-nav-bar href="{{ route('admin.activitylogs') }}" 
                                                class="{{ request()->routeIs('admin.activitylogs') ? 'active' : '' }} subnav2">
                                    <svg style="margin-right: 3px;" width="33" height="33" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.0714 17.5L16.5021 16.9307C16.1806 16.6092 16 16.1731 16 15.7185V14M6 20H5C3.89543 20 3 19.1046 3 18V10M21 7V6.5C21 5.39543 20.1046 4.5 19 4.5H18M3 10H6M3 10V6.5C3 5.39543 3.89543 4.5 5 4.5H6M18 3V4.5M18 4.5H12M12 3V4.5M12 4.5H6M6 3V4.5M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>Activity Logs
                                </x-side-nav-bar>
                            </li>
                        </ul>
                    <li>
            </ul>

        </nav>
    </aside>

    <main class="main"> 
        {{$slot}}
    </main>
</div>

<script>
    function toggleSubmenu(event) {
        event.preventDefault();
        const userSubmenu = document.getElementById('user-submenu');
        const arrow = document.querySelector('.arrow');

        if (userSubmenu.style.display === "block") {
            userSubmenu.style.display = "none"; 
            arrow.textContent = "▶";  
        } else if (userSubmenu.style.display === "" || userSubmenu.style.display === "none"){
            userSubmenu.style.display = "block"; 
            arrow.textContent = "▼";  
        }
    }

    function toggleSubmenu2(event) {
        event.preventDefault();
        const userSubmenu = document.getElementById('user-submenu2');
        const arrow = document.querySelector('.arrow2');

        if (userSubmenu.style.display === "block") {
            userSubmenu.style.display = "none"; 
            arrow.textContent = "▶";  
        } else if (userSubmenu.style.display === "" || userSubmenu.style.display === "none"){
            userSubmenu.style.display = "block"; 
            arrow.textContent = "▼";  
        }
    }

    function handleLogout(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        if (form) {
            form.submit();
        }
    }
</script>
</body>
</html>
