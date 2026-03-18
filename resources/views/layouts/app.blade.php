<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body class="mesh-bg">
    <!-- V4 Noise Texture -->
    <div class="noise-overlay"></div>

    <x-alert />
    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    <style>
        /* Ultra Modern Animations & Utilities */
        @keyframes gradient-mesh {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient-mesh {
            background-size: 200% 200%;
            animation: gradient-mesh 15s ease infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float-delayed {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-float-delayed {
            animation: float-delayed 7s ease-in-out infinite 1s;
        }
        
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(var(--accent-rgb), 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(var(--accent-rgb), 0); }
            100% { box-shadow: 0 0 0 0 rgba(var(--accent-rgb), 0); }
        }
        .animate-pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        /* 3D Tilt Utilities */
        .perspective-1000 { perspective: 1000px; }
        .transform-style-3d { transform-style: preserve-3d; }
        
        /* Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-panel-dark {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Typewriter Cursor */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        .typewriter-cursor::after {
            content: '|';
            animation: blink 1s step-end infinite;
            color: var(--accent);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <!-- Global V4 Scripts -->
    <script>
        document.addEventListener('turbo:load', () => {
            const observerOptions = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-on-scroll').forEach(el => {
                observer.observe(el);
            });

            // Counter Animation Logic
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const endValue = parseInt(target.getAttribute('data-target'));
                        const duration = 2000; // 2 seconds
                        const frameRate = 1000 / 60; // 60fps
                        const totalFrames = Math.round(duration / frameRate);
                        let currentFrame = 0;
                        
                        const counter = setInterval(() => {
                            currentFrame++;
                            const progress = currentFrame / totalFrames;
                            // Ease out current progress
                            const currentCount = Math.round(endValue * (1 - Math.pow(1 - progress, 3)));
                            
                            // Add formatting if needed (K, +, etc)
                            let suffix = target.getAttribute('data-suffix') || '';
                            let prefix = target.getAttribute('data-prefix') || '';
                            
                            target.innerText = prefix + currentCount + suffix;
                            
                            if (currentFrame === totalFrames) {
                                clearInterval(counter);
                                target.innerText = prefix + endValue + suffix;
                            }
                        }, frameRate);
                        
                        counterObserver.unobserve(target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-counter').forEach(el => {
                counterObserver.observe(el);
            });
        });
    </script>
</body>

</html>
