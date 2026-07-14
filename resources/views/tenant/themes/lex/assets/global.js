/**
 * lex THEME — Global JavaScript
 * Arzavo Theme System v2.0
 * 
 * Features:
 * - Scroll reveal animations (IntersectionObserver)
 * - Navbar scroll effect (transparent → solid)
 * - Smooth scroll for anchor links
 * - Counter animation for stat numbers
 * - Mobile menu (drawer + overlay)
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initlex);
        document.addEventListener('turbo:load', initlex);
    } else {
        initlex();
    }

    function initlex() {
        initScrollReveal();
        initNavbarScroll();
        initSmoothScroll();
        initCounterAnimation();
        initMobileMenu();
        initAccordions();
        initSliders();
    }


    /* ================================
       ✨ SCROLL REVEAL
       ================================ */
    function initScrollReveal() {
        var sections = document.querySelectorAll('[data-theme="lex"] [data-section-id]');

        if (!sections.length || !('IntersectionObserver' in window)) return;

        // Add reveal class to all sections
        sections.forEach(function(section) {
            // Skip navbar and fixed elements
            var type = section.getAttribute('data-section-type');
            if (type === 'navbar' || type === 'announcement_bar' || type === 'utility_bar') return;

            section.classList.add('lex-reveal');
        });

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('lex-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px'
        });

        sections.forEach(function(section) {
            if (section.classList.contains('lex-reveal')) {
                observer.observe(section);
            }
        });
    }


    /* ================================
       🧭 NAVBAR SCROLL EFFECT
       ================================ */
    function initNavbarScroll() {
        var navbar = document.querySelector('[data-theme="lex"] [data-section-type="navbar"]');

        if (!navbar) return;

        var scrollThreshold = 50;
        var ticking = false;

        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (window.scrollY > scrollThreshold) {
                        navbar.classList.add('lex-nav-scrolled');
                    } else {
                        navbar.classList.remove('lex-nav-scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // Initial check
    }


    /* ================================
       🔗 SMOOTH SCROLL
       ================================ */
    function initSmoothScroll() {
        document.querySelectorAll('[data-theme="lex"] a[href^="#"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                var targetId = this.getAttribute('href');
                if (targetId === '#') return;

                var target = document.querySelector(targetId);
                if (!target) return;

                e.preventDefault();

                var navHeight = 80;
                var navbar = document.querySelector('[data-section-type="navbar"]');
                if (navbar) {
                    navHeight = navbar.offsetHeight;
                }

                var top = target.getBoundingClientRect().top + window.pageYOffset - navHeight;

                window.scrollTo({
                    top: top,
                    behavior: 'smooth'
                });
            });
        });
    }


    /* ================================
       🔢 COUNTER ANIMATION
       ================================ */
    function initCounterAnimation() {
        var counters = document.querySelectorAll('[data-theme="lex"] [data-lex-counter]');

        if (!counters.length || !('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(function(counter) {
            observer.observe(counter);
        });
    }

    function animateCounter(el) {
        var target = parseInt(el.getAttribute('data-lex-counter'), 10);
        var suffix = el.getAttribute('data-lex-suffix') || '';
        var prefix = el.getAttribute('data-lex-prefix') || '';
        var duration = 2000; // ms
        var startTime = null;

        if (isNaN(target)) return;

        function step(currentTime) {
            if (!startTime) startTime = currentTime;
            var progress = Math.min((currentTime - startTime) / duration, 1);

            // Easing: ease-out cubic
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = Math.floor(eased * target);

            el.textContent = prefix + current.toLocaleString() + suffix;

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = prefix + target.toLocaleString() + suffix;
            }
        }

        requestAnimationFrame(step);
    }


    /* ================================
       📱 MOBILE MENU
       ================================ */
    function initMobileMenu() {
        // Make toggleMobileMenu globally available
        window.toggleMobileMenu = function() {
            var overlay = document.querySelector('.lex-mobile-overlay');
            var drawer = document.querySelector('.lex-mobile-drawer');

            if (!overlay || !drawer) return;

            var isOpen = drawer.classList.contains('lex-open');

            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        };

        // Close on overlay click
        var overlay = document.querySelector('.lex-mobile-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeMobileMenu);
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });
    }

    function openMobileMenu() {
        var overlay = document.querySelector('.lex-mobile-overlay');
        var drawer = document.querySelector('.lex-mobile-drawer');

        if (overlay) overlay.classList.add('lex-open');
        if (drawer) drawer.classList.add('lex-open');

        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        var overlay = document.querySelector('.lex-mobile-overlay');
        var drawer = document.querySelector('.lex-mobile-drawer');

        if (overlay) overlay.classList.remove('lex-open');
        if (drawer) drawer.classList.remove('lex-open');

        document.body.style.overflow = '';
    }


    /* ================================
       ❓ ACCORDION
       ================================ */
    function initAccordions() {
        // Handled inline in accordion.blade.php to avoid load order and double-toggle issues
    }


    /* ================================
       🎠 SLIDESHOW / SLIDER
       ================================ */
    function initSliders() {
        document.querySelectorAll('[data-theme="lex"] [data-slider]').forEach(function(slider) {
            if (slider.dataset.initialized) return;
            slider.dataset.initialized = "true";

            var track = slider.querySelector('.slider-track');
            if (!track) return;

            var slides = Array.from(track.children);
            if (slides.length <= 1) return;

            var prevBtn = slider.querySelector('.slider-prev');
            var nextBtn = slider.querySelector('.slider-next');
            var dotsWrapper = slider.querySelector('.slider-dots');

            var autoplayEnabled = slider.dataset.autoplay === '1';
            var delayTime = parseInt(slider.dataset.delay || 3000, 10);

            var currentIndex = 0;
            var autoplayTimer;
            var startX = 0;
            var isDragging = false;

            function updateSlidePosition() {
                track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
                updateDots();
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlidePosition();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateSlidePosition();
            }

            function goToSlide(index) {
                currentIndex = index;
                updateSlidePosition();
            }

            function setupDots() {
                if (!dotsWrapper) return;
                dotsWrapper.innerHTML = '';

                slides.forEach(function(_, i) {
                    var btn = document.createElement('button');
                    btn.className = i === 0 ?
                        'h-2 w-8 rounded-full bg-white transition-all duration-300' :
                        'h-2 w-2 rounded-full bg-white/50 hover:bg-white/70 transition-all duration-300';
                    btn.ariaLabel = 'Go to slide ' + (i + 1);

                    btn.addEventListener('click', function() {
                        stopAutoplay();
                        goToSlide(i);
                        startAutoplay();
                    });

                    dotsWrapper.appendChild(btn);
                });
            }

            function updateDots() {
                if (!dotsWrapper) return;

                Array.from(dotsWrapper.children).forEach(function(btn, i) {
                    if (i === currentIndex) {
                        btn.className = 'h-2 w-8 rounded-full bg-white transition-all duration-300';
                    } else {
                        btn.className = 'h-2 w-2 rounded-full bg-white/50 hover:bg-white/70 transition-all duration-300';
                    }
                });
            }

            function startAutoplay() {
                if (!autoplayEnabled) return;
                stopAutoplay();
                autoplayTimer = setInterval(nextSlide, delayTime);
            }

            function stopAutoplay() {
                if (autoplayTimer) clearInterval(autoplayTimer);
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    stopAutoplay();
                    nextSlide();
                    startAutoplay();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    stopAutoplay();
                    prevSlide();
                    startAutoplay();
                });
            }

            slider.addEventListener('mouseenter', stopAutoplay);
            slider.addEventListener('mouseleave', startAutoplay);

            slider.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isDragging = true;
                stopAutoplay();
            }, { passive: true });

            slider.addEventListener('touchend', function(e) {
                if (!isDragging) return;
                var endX = e.changedTouches[0].clientX;
                var diff = startX - endX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) nextSlide();
                    else prevSlide();
                }
                isDragging = false;
                startAutoplay();
            }, { passive: true });

            setupDots();
            startAutoplay();
        });
    }

})();

