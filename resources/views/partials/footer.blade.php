<div class="footer-section mt-15 py-5" style="background-color: var(--secondary-background);">
    <div class="container">
        <div class="footer py-4 grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
            <div class="section-one">
                <a href="{{ url('/') }}">
                    <img id="logo" src="{{ asset($customizes['logo'] ?? 'images/ARZAQ-dark-logo.png') }}" alt="Logo" class="logo w-auto" style="height: {{ $customizations->logo_size ?? 50 }}px;">
                </a>
                <p class="mt-2 max-w-2/3" style="color: var(--secondary-text-color);">PJC provides expert NEET and IIT-JEE coaching with strong concepts, personal support, and result-focused learning.</p>
                <ul class="social-links flex gap-4 mt-4">
                    <li><a href="#" style="color: var(--secondary-text-color);"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li><a href="#" style="color: var(--secondary-text-color);"><i class="fa-brands fa-twitter"></i></a></li>
                    <li><a href="#" style="color: var(--secondary-text-color);"><i class="fa-brands fa-instagram"></i></a></li>
                </ul>
            </div>
            <div class="section-two">
                <h3 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">Quick Links</h3>
                <ul class="quick-links">
                    <li><a href="{{ url('/') }}" style="color: var(--secondary-text-color);">Home</a></li>
                    <li><a href="{{ url('/courses') }}" style="color: var(--secondary-text-color);">Courses</a></li>
                    <li><a href="{{ url('/test-series') }}" style="color: var(--secondary-text-color);">Test Series</a></li>
                    <li><a href="{{ url('/books') }}" style="color: var(--secondary-text-color);">Books</a></li>
                    <li><a href="{{ url('/contact') }}" style="color: var(--secondary-text-color);">Contact Us</a></li>
                </ul>
            </div>
            <div class="section-three">
                <h3 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">Contact Us</h3>
                <p class="mb-2" style="color: var(--secondary-text-color);"><i class="fa-solid fa-map-marker-alt mr-2"></i> {{ $settings['address'] ?? 'Block D, New Ashok Nagar, Delhi, 110096' }}</p>
                <p class="mb-2" style="color: var(--secondary-text-color);"><i class="fa-solid fa-phone mr-2"></i>{{ $settings['phone'] ?? '9198599490' }}</p>
                <p class="mb-2" style="color: var(--secondary-text-color);"><i class="fa-solid fa-envelope mr-2"></i><a href="mailto:{{ $settings['email'] ?? 'arzaqinsights@gmail.com' }}">{{ $settings['email'] ?? 'arzaqinsights@gmail.com' }}</a></p>
            </div>
        </div>
    </div>
</div>