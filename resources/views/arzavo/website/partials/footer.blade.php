{{-- Website Footer (TENANTS_DESIGN_SYSTEM Compliant) --}}
<footer class="bg-primary text-theme-text transition-colors mt-20">
    <div class="container py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 mb-12">

            {{-- Column 1: Brand & Overview (4 cols) --}}
            <div class="lg:col-span-4 pr-0 lg:pr-8">
                <!-- Logo -->
            <a href="{{ route('tenants.index') }}" class="shrink-0 flex items-center gap-2 mb-6">
                <img class="theme-light-logo h-10 w-auto object-contain"
                    src="{{ media('images/logo/arzavo-dark.png') }}" alt="Logo">
                <img class="theme-dark-logo h-10 w-auto object-contain"
                    src="{{ media('images/logo/arzavo-white.png') }}" alt="Logo">
            </a>
                <p class="text-theme-text-muted text-xs leading-relaxed mb-6 max-w-sm font-normal">
                    The enterprise multi-tenant educational operating system. Empowering modern schools, academies, and coaching institutes with custom domains, isolated databases, and comprehensive LMS automation.
                </p>

                {{-- Newsletter Block --}}
                <div class="mb-6 max-w-sm">
                    <p class="text-xs font-semibold text-theme-text mb-2.5">Subscribe to platform updates</p>
                    <div class="flex items-center bg-secondary p-1 rounded-full">
                        <div class="pl-3 text-theme-text-muted text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email"
                               placeholder="Enter your email"
                               class="flex-1 bg-transparent px-3 py-2 text-xs text-theme-text placeholder:text-theme-text-muted focus:outline-none">
                        <button type="button"
                                class="shrink-0 px-4 py-2 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 transition">
                            Subscribe
                        </button>
                    </div>
                </div>

                {{-- Social Channels --}}
                <div class="flex items-center gap-2">
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                       aria-label="Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                       aria-label="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                       aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                       aria-label="YouTube">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>

            {{-- Column 2: Solutions (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-theme-text mb-4">Solutions</h4>
                <ul class="space-y-2.5 text-xs">
                    @foreach([
                        'Schools & K-12' => 'solutions',
                        'Coaching Centers' => 'solutions',
                        'Digital Academies' => 'solutions',
                        'Skill Labs' => 'solutions'
                    ] as $name => $anchor)
                    <li>
                        <a href="{{ route('home') }}#{{ $anchor }}"
                           class="text-theme-text-muted hover:text-theme-text transition font-normal block">
                            {{ $name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Column 3: Platform (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-theme-text mb-4">Platform</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="{{ route('features') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Features</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Pricing</a></li>
                    <li><a href="{{ route('contact') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Contact Support</a></li>
                    <li><a href="{{ route('documentation.index') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Documentation</a></li>
                    <li><a href="{{ route('trust') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Trust Center</a></li>
                </ul>
            </div>

            {{-- Column 4: Legal & Policy (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-theme-text mb-4">Legal</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="{{ route('privacy') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Terms of Service</a></li>
                    <li><a href="{{ route('refunds') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Refund Policy</a></li>
                    <li><a href="{{ route('cookies') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Cookie Policy</a></li>
                    <li><a href="{{ route('retention') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Data Retention</a></li>
                    <li><a href="{{ route('aup') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Acceptable Use</a></li>
                </ul>
            </div>

            {{-- Column 5: Privacy & Compliance (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-theme-text mb-4">Security</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="{{ route('security') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Security Policy</a></li>
                    <li><a href="{{ route('ownership') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Data Ownership</a></li>
                    <li><a href="{{ route('student-privacy') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Student Privacy</a></li>
                    <li><a href="{{ route('communication-policy') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Consent Policy</a></li>
                    <li><a href="{{ route('dpa') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">DPA Agreement</a></li>
                    <li><a href="{{ route('legal') }}" class="text-theme-text-muted hover:text-theme-text transition font-normal block">Legal Notices</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Copyright Strip (No border, bg-secondary separation) --}}
        <div class="bg-secondary rounded-lg px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-xs text-theme-text-muted font-normal">
                &copy; {{ date('Y') }} <span class="text-theme-text font-semibold">Arzavo</span> by ARZAQ INSIGHTS. All rights reserved.
            </p>
            <div class="flex items-center gap-4 text-xs text-theme-text-muted font-normal">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-success"></i> Zero-Trust Physical Data Isolation
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-lock text-success"></i> Hosted Securely in AWS Mumbai
                </span>
            </div>
        </div>
    </div>
</footer>
