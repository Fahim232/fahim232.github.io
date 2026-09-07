(function () {
    'use strict';

    const header = document.getElementById('header');
    const navMenu = document.getElementById('nav-menu');
    const navToggle = document.getElementById('nav-toggle');
    const navClose = document.getElementById('nav-close');
    const navLinks = document.querySelectorAll('.nav__link');
    const sections = document.querySelectorAll('section[id]');

    // Mobile menu toggle
    if (navToggle) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }

    if (navClose) {
        navClose.addEventListener('click', function () {
            navMenu.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    // Close menu when clicking a link (mobile)
    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            navMenu.classList.remove('show');
            document.body.style.overflow = '';
        });
    });

    // Header scroll effect
    if (header) {
        function onScroll() {
            if (window.scrollY > 50) {
                header.style.background = 'rgba(15, 15, 18, 0.95)';
            } else {
                header.style.background = 'rgba(15, 15, 18, 0.85)';
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // Active nav link based on scroll position
    function setActiveNav() {
        const scrollY = window.scrollY;
        const headerOffset = 120;

        sections.forEach(function (section) {
            const id = section.getAttribute('id');
            const top = section.offsetTop - headerOffset;
            const height = section.offsetHeight;

            if (scrollY >= top && scrollY < top + height) {
                navLinks.forEach(function (link) {
                    link.classList.remove('is-active');
                    if (link.getAttribute('href') === '#' + id) {
                        link.classList.add('is-active');
                    }
                });
            }
        });
    }

    window.addEventListener('scroll', function () {
        requestAnimationFrame(setActiveNav);
    }, { passive: true });
    setActiveNav();

    // Intersection Observer for simple scroll reveals
    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    // Optional: stop observing once revealed
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '0px',
            threshold: 0.15
        });

        revealEls.forEach(function (el) {
            observer.observe(el);
        });
    }

    // Typing effect for hero subtitle
    const subtitle = document.querySelector('.hero__subtitle');
    if (subtitle) {
        const text = subtitle.textContent;
        subtitle.textContent = '';
        subtitle.style.opacity = '1';
        subtitle.style.transform = 'none';

        let i = 0;
        function type() {
            if (i < text.length) {
                subtitle.textContent += text.charAt(i);
                i++;
                setTimeout(type, 50);
            }
        }

        // Start typing after a short delay
        setTimeout(type, 800);
    }
})();
