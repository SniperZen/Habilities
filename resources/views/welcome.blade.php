<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilities Center for Intervention</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('jss/welcome.js') }}"></script>

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script>
  window.onload = function () {
    // document.body.classList.add("fade-in");

    let lastScrollY = window.scrollY;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const currentScrollY = window.scrollY;

          // Determine fade-in direction and apply appropriate classes
          if (entry.target.classList.contains("fade-in-up") || entry.target.classList.contains("fade-in-down")) {
            if (currentScrollY > lastScrollY) {
              entry.target.classList.remove("fade-in-down");
              entry.target.classList.add("fade-in-up", "visible");
            } else {
              entry.target.classList.remove("fade-in-up");
              entry.target.classList.add("fade-in-down", "visible");
            }
            lastScrollY = currentScrollY;
          } else if (entry.target.classList.contains("fade-in-left")) {
            entry.target.classList.add("fade-in-left", "visible");
          } else if (entry.target.classList.contains("fade-in-right")) {
            entry.target.classList.add("fade-in-right", "visible");
          }
        } else {
          // Reset animation when element is out of viewport
          entry.target.classList.remove("visible");
        }
      });
    });

    // Target all fade-in elements
    const sections = document.querySelectorAll(".fade-in-up, .fade-in-down, .fade-in-left, .fade-in-right");
    sections.forEach((section) => observer.observe(section));
  };
</script>

    
</head>

<body>
<div class="container fade-in">
    <!-- Header -->
    <header id="header">
        <div class="logo-title">
            <svg @click="openSidebar = !openSidebar" class="hamburger logos" width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.02771 16.0466L27.0277 16.0085" stroke="#4f6b4a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.01392 8.04663L27.0139 8.00848" stroke="#4f6b4a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.04163 24.0466L27.0416 24.0085" stroke="#4f6b4a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <img src="images/logo.png" alt="Logo">
            <h1>Habilities Center for Intervention</h1>
        </div>

        <nav>
            <div class="navrad1">
                <div class="navrad2" :class="{'hidden': openSidebar}" x-show="!openSidebar">
                    <a href="#about">About</a>
                    <a href="#servicess">Services</a>
                    <a href="#cases">Cases</a>
                    <a href="#contact">Contact</a>
                </div>
                <a href="{{ route('login') }}">
                    <div class="navrad">
                        Log in
                    </div>
                </a>
            </div>
        </nav>
    </header>



    <div class="sidebar-menu" :class="{'active': openSidebar}">
    <svg @click="!openSidebar = openSidebar" class="hamburger logos" width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.02771 16.0466L27.0277 16.0085" stroke="#4f6b4a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.01392 8.04663L27.0139 8.00848" stroke="#4f6b4a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.04163 24.0466L27.0416 24.0085" stroke="#4f6b4a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        <nav>
            <a href="#about"><i class="fas fa-info-circle"></i> About</a>
            <a href="#services"><i class="fas fa-hand-holding-heart"></i> Services</a>
            <a href="#cases"><i class="fas fa-heartbeat"></i> Cases</a>
            <a href="#contact"><i class="fas fa-envelope"></i> Contact</a>
        </nav>
    </div>



        <div class="content">
            <!-- hero Section -->
            <section class="hero" style="background: url('images/wbg.png') center center/cover no-repeat;">
                <div class="leftp fade-in-up">
                    <h2>YOUR PATH TO FINDING EASE WITH THERAPEASE</h2>
                    <p>We are dedicated to creating a safe and nurturing environment where you can explore your thoughts and emotions, fostering growth and healing at your own pace.</p>
                    <a href="{{ route('account.type.selection') }}"><button>Get Started</button></a>
                </div>
                <div class="rpic fade-in-up">
                    <img src="{{ asset('images/rpic.png') }}" alt="">
                </div>
            </section>


            <!-- Features Section -->
            <section class="features">
                <div class="param fade-in-up">
                    <h3>WHAT WE DO</h3>
                    <p>Our goal is to provide individuals with access to therapy services fade-in-up through a user-friendly platform, while also offering tools and resources to help users improve their mental and emotional well-being.</p>
                </div>
                <div class="fs">
                    <!-- Individual Features -->
                    <div class="feature fade-in-up">
                        <div><img src="{{ asset('images/welcome/therap.png') }}" alt=""></div>
                        <h2>Qualified <br> Professionals</h2>
                        <p>Receive quality care from our licensed and experienced therapists who are compassionate and skilled in various therapeutic techniques.</p>
                    </div>
                    <div class="feature fade-in-up">
                        <div><img src="{{ asset('images/welcome/hands.png') }}" alt=""></div>
                        <h2>Customized <br> Support</h2>
                        <p>Offer ongoing support and follow-up care to clients even after their initial therapy sessions have ended.</p>
                    </div>
                    <div class="feature fade-in-up">
                        <div><img src="{{ asset('images/welcome/healthlove.png') }}" alt=""></div>
                        <h2>Collaborative <br> Approach</h2>
                        <p>Encourage open communication and feedback to ensure that therapy aligns with the client's goals and preferences, leading to a more empowering and effective therapeutic experience.</p>
                    </div>

                </div>
            </section>


            <!-- Vision Section -->
        <section class="vision-mission" id="about">
            <div class="dchild fade-in-up"><img src="{{ asset('images/dchild.png') }}" alt=""></div>
                <div class="vm">
                    <div class="vision fade-in-up">
                        <h2>OUR VISION</h2>
                        <p>
                            This center began as a dream that was placed on the hearts of a chosen few.
                            The journey we have traveled to lead us here has been filled with miracles
                            and blessings. We have sought guidance for every decision made on behalf
                            of this center and the answers have been clear.
                        </p>
                        <p>
                            We believe every child, family member, habilitation provider, therapist, 
                            and teacher has been picked from above to fill and bless these walls. We 
                            vow to provide your child or loved one with exceptional care and treatment. 
                            Children with special needs have an inherent purity and beauty that we are 
                            honored to be in the presence of.
                        </p>
                        <p>
                            We take very seriously the high calling to teach, nurture, and train these 
                            wonderful children. Their growth and development will be handled with utmost 
                            care. We are honored by those who seek out services fade-in-up for someone they love.
                        </p>
                    </div>

                    <div class="mision fade-in-up">
                    <h2>OUR MISSION</h2>
                        <p>Habilities center for intervention will Maintain a facility where a child with special Needs can come and experience a loving And nurturing atmosphere while receiving Their therapies and developing the child potential and skills.
                        </p>
                    </div>
                </div>
            </section>

            <section class="service" id="servicess">
                <div class="serve">
                    <h3>Services Offered</h3>
                    <div class="service1 fade-in-left">
                    <div><img src="{{asset('images/s1.png')}}" alt="SpEd Tutorials" class="service-img"></div>
                    <div><h3>SpEd Tutorials</h3>
                        <p>Special Education provides basic academic skills, pre vocational, vocational, transition, Independent living skills, self help and socialization skills.</p>
                    </div></div>
                    <div class="services fade-in-right">
                    <div><img src="{{asset('images/s2.png')}}" alt="Speech Therapy" class="service-img"></div>
                        <div><h3>Speech Therapy</h3>
                        <p>Speech Therapy enhances communication skills through personalized exercises and activities, promoting effective verbal expression and comprehension.</p>
                    </div></div>
                    <div class="service1 fade-in-left">
                        <div><img src="{{asset('images/s3.png')}}" alt="Occupational Therapy" class="service-img"></div>
                        <div><h3>Occupational Therapy</h3>
                        <p>Occupational Therapy helps children develop necessary skills for daily activities, focusing on motor skills, sensory processing, and coordination.</p></div>
                    </div></div>
                    <div class="services fade-in-right">
                    <div><img src="{{asset('images/s4.png')}}" alt="Shadow Teacher" class="service-img"></div>
                        <div><h3>Shadow Teacher</h3>
                        <p>A shadow teacher offers in-class support to children with special needs, ensuring they receive personalized attention and guidance during classroom activities.</p>
                    </div></div>
                    <div class="service1 fade-in-left">
                    <div><img src="{{asset('images/s5.png')}}" alt="Early Childhood Intervention" class="service-img"></div>
                        <div><h3>Early Childhood Intervention</h3>
                        <p>Early childhood intervention programs support the development of infants and toddlers with special needs, promoting growth in cognitive, physical, and social-emotional areas.</p>
                    </div></div>
                    <div class="services fade-in-right">
                    <div><img src="{{asset('images/s6.png')}}" alt="Developmental Pediatrician" class="service-img"></div>
                        <div><h3>Developmental Pediatrician</h3>
                        <p>Developmental pediatricians specialize in diagnosing and managing developmental disorders, providing tailored treatment plans for children with special needs.</p>
                    </div></div>
                </div>
            </section>


    <section class="cases" id="cases">
        <div class="case">
                <div class="head fade-in-up">
                    <p class="know-more">Know more</p>
                    <h2 class="section-title">Cases Handled</h2>
                    <p class="description">Habilities Center for Intervention will maintain a facility where people with special needs can come and receive therapies to develop their potential and skills.</p>
                </div>
            <div class="carousel-controls fade-in-up">
                <div><button class="prev" onclick="prevSlide()">&#8249;</button></div>
                <div class="carousel-container">
                    <div class="carousel">
                        <div class="case-box">
                                <p class="definition">
                                    Our therapy center supports children with Autism Spectrum Disorder (ASD) through tailored interventions that enhance communication, social skills, and daily living. We use evidence-based strategies to create individualized programs, nurturing each child’s unique strengths in an inclusive environment.
                                </p>
                                <h1 class="title">Children with Autism</h1>
                            <img src="{{ asset('images/welcome/1.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                    We help children with ADHD by implementing strategies for focus, organization, and self-regulation. Our therapists collaborate with families and educators to create personalized interventions, empowering each child to succeed academically and socially.
                                </p>
                                <h1 class="title">Attention Deficit Hyperactivity Disorder (ADHD)</h1>
                            <img src="{{ asset('images/welcome/2.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                    Our center provides comprehensive support for children with Cerebral Palsy through a multidisciplinary approach, including physical, occupational, and speech therapy. Our goal is to enhance mobility, improve daily functioning, and help children develop skills for full participation in life.
                                </p>
                                <h1 class="title">Cerebral Palsy</h1>
                            <img src="{{ asset('images/welcome/3.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                    We support children with Down Syndrome through personalized interventions that enhance developmental skills in speech, motor skills, and socialization, fostering growth, confidence, and independence.
                                </p>
                                <h1 class="title">Down Syndrome</h1>
                            <img src="{{ asset('images/welcome/4.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                    Our therapy center specializes in Global Developmental Delay (GDD), providing individualized programs that target cognitive, motor, language, and social skills. We ensure each child receives the support needed to reach their developmental milestones and thrive.
                                </p>
                                <h1 class="title">Global Developmental Delay (GDD)</h1>
                            <img src="{{ asset('images/welcome/5.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                    We provide support for children with behavioral problems, helping them develop positive coping strategies and social skills. Our team uses evidence-based interventions tailored to each child’s unique challenges, working with families to foster emotional well-being and positive behavior.
                                </p>
                                <h1 class="title">Behavioral Problems</h1>
                            <img src="{{ asset('images/welcome/6.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                Our center supports children with hearing impairments through specialized therapy that enhances communication and social skills. We collaborate with families and educators to create individualized plans that promote language development and full participation in their environments.
                                </p>
                                <h1 class="title">Hearing Impairments</h1>
                            <img src="{{ asset('images/welcome/7.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                We support children with learning disabilities through tailored interventions that address specific academic challenges. Our dedicated team collaborates with each child to develop effective strategies for reading, writing, and math, fostering a positive learning environment that promotes confidence and success.
                                </p>
                                <h1 class="title">Learning Disabilities</h1>
                            <img src="{{ asset('images/welcome/8.png') }}" alt="Children with Autism">
                        </div>
                        <div class="case-box">
                                <p class="definition">
                                Our therapy center offers targeted support for children with motor delays, enhancing gross and fine motor skills. Through engaging activities, our skilled therapists help improve strength, coordination, and physical abilities, empowering children to achieve independence in daily activities.
                                </p>
                                <h1 class="title">Motor Delays</h1>
                            <img src="{{ asset('images/welcome/9.png') }}" alt="Children with Autism">
                        </div>
                    </div>
                </div>
                <div><button class="next" onclick="nextSlide()">&#8250;</button></div>
            </div>
        </div>
        <script>
            let currentSlide = 0;
            const carousel = document.querySelector('.carousel');
            const totalSlides = document.querySelectorAll('.case-box').length;
            const slideWidth = document.querySelector('.case-box').offsetWidth + 20; // Include gap between slides

            function updateCarouselPosition() {
                carousel.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
            }

            function nextSlide() {
                if (currentSlide < totalSlides - 1) {
                    currentSlide++;
                } else {
                    currentSlide = 0; // Loop back to the start
                }
                updateCarouselPosition();
            }

            function prevSlide() {
                if (currentSlide > 0) {
                    currentSlide--;
                } else {
                    currentSlide = totalSlides - 1; // Loop back to the end
                }
                updateCarouselPosition();
            }
        </script>
    </section>

            <section class="contact" id="contact">
                <div class="contacts">
                    <div>
                        <h3 class="fade-in-up">Contact Us!</h3></div>
                        <div class="contacts1">
                            <div class="contacts2">
                            <div class="contact-info fade-in-up">
                                <h3>Habilities Center For Intervention</h3>
                                <p>
                                    <strong>Address</strong><br>
                                    112 Sampaguita Street, Phase 1<br>
                                    Brgy. Bulihan, City Of Malolos<br>
                                    3000 Bulacan, Philippines
                                </p>
                                <p>
                                    <strong>Hours</strong><br>
                                    @php
                                        $days = [
                                            'sunday' => 'Sun',
                                            'monday' => 'Mon',
                                            'tuesday' => 'Tue',
                                            'wednesday' => 'Wed',
                                            'thursday' => 'Thurs',
                                            'friday' => 'Fri',
                                            'saturday' => 'Sat'
                                        ];
                                    @endphp

                                    @foreach($days as $dayKey => $dayLabel)
                                        @php
                                            $hours = $settings->business_hours[$dayKey] ?? null;
                                            $isClosed = $hours['is_closed'] ?? true;
                                            $isTeletherapy = $hours['is_teletherapy'] ?? false;
                                            $startTime = $hours['start_time'] ?? '';
                                            $endTime = $hours['end_time'] ?? '';
                                        @endphp

                                        {{ $dayLabel }}: 
                                        @if($isClosed)
                                            Closed
                                        @elseif($isTeletherapy)
                                            <span class="teletherapy-badge">Tele-therapy Only</span>
                                        @else
                                            {{ \Carbon\Carbon::parse($startTime)->format('h:i A') }} - {{ \Carbon\Carbon::parse($endTime)->format('h:i A') }}
                                        @endif
                                        <br>
                                    @endforeach

                                </p>
                                <p>
                                    <strong>Contact</strong><br>
                                    Phone: {{ $settings->phone ?? 'Not Available' }}<br>
                                    Email: {{ $settings->email ?? 'Not Available' }}
                                </p>
                            </div>
                            <div class="map fade-in-up">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.3603880628225!2d120.80403717587748!3d14.861113170643938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339653df18a2d6a5%3A0xac6ca8ce5b8e66fb!2sHabilities%20Center%20For%20Intervention!5e0!3m2!1sen!2sus!4v1725507566462!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </div>
            <footer class="footer">
            <div class="footer-content fade-in-up">
                <div class="footer-section about">
                    <h3>Habilities Center for Intervention</h3>
                    <p>
                        112 Sampaguita Street, Phase 1<br>
                        Brgy. Bulihan, City Of Malolos,<br>
                        3000 Bulacan, Philippines
                    </p>
                    <p><strong>Phone:</strong> {{ $settings->phone ?? 'Not Available' }}</p>
                    <p><strong>Email:</strong> {{ $settings->email ?? 'Not Available' }}</p>
                </div>

                <div class="footer-section links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services fade-in-up">Services</a></li>
                        <li><a href="#cases">Cases</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-section social-media">
                    <h3>Follow Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Habilities Center for Intervention. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
