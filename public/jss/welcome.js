document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

let lastScrollTop = 0;
        const header = document.querySelector('header');
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            header.style.top = scrollTop > lastScrollTop ? '-1000px' : '0';
            lastScrollTop = scrollTop;
        });


    //hamburger menu

    const hamburger = document.querySelector('.hamburger');
    const sidebarMenu = document.querySelector('.sidebar-menu');

    hamburger.addEventListener('click', () => {
        sidebarMenu.classList.toggle('active');
    });

    document.addEventListener('click', (event) => {
        if (!sidebarMenu.contains(event.target) && !hamburger.contains(event.target)) {
            sidebarMenu.classList.remove('active');
        }
    });
});
