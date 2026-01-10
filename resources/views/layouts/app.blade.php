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
        });
    </script>
</body>

</html>