{{-- Footer --}}
<footer class="relative overflow-hidden pt-16 pb-8 border-t border-gray-200"
        style="background: linear-gradient(180deg, #f9f9f9 0%, #ffffff 100%);">

    <div class="container relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-12">

            {{-- Column 1: Brand & Updates (4 cols) --}}
            <div class="lg:col-span-4 pr-0 lg:pr-8">
                <a href="{{ route('home') }}" class="inline-block mb-4">
                    <img src="{{ asset('images/logo/arzavo-dark.png') }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/logo/arzavo-white.png') }}'"
                         alt="Arzavo" class="h-12">
                </a>
                <p class="text-dark/50 text-xs leading-relaxed mb-6 max-w-sm">
                    The complete ERP & LMS platform for modern educational institutions. Launch, scale, and thrive in the digital age.
                </p>

                {{-- Newsletter --}}
                <div class="mb-6 max-w-sm">
                    <p class="text-xs font-semibold text-dark mb-3">Subscribe to updates</p>
                    <div class="flex items-center rounded border border-gray-200 bg-white p-1 focus-within:border-accent/40 transition-colors duration-200">
                        <div class="pl-2 text-dark/30">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input type="email" placeholder="Your email"
                               class="flex-1 bg-transparent border-none px-2 py-1 text-xs text-dark placeholder-dark/30 focus:ring-0 outline-none">
                        <button class="shrink-0 text-white bg-accent px-3 py-1.5 rounded text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer">
                            Subscribe
                        </button>
                    </div>
                </div>

                {{-- Social Icons --}}
                <div class="flex gap-2">
                    <a href="#" class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-all duration-300">
                        <i class="fa-brands fa-x-twitter text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-all duration-300">
                        <i class="fa-brands fa-linkedin-in text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-all duration-300">
                        <i class="fa-brands fa-instagram text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-all duration-300">
                        <i class="fa-brands fa-youtube text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- Column 2: Solutions (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-dark mb-4">Solutions</h4>
                <ul class="space-y-2.5">
                    @foreach([
                        'Schools & K-12' => 'solutions',
                        'Coaching Centers' => 'solutions',
                        'Digital Academies' => 'solutions',
                        'Skill Labs' => 'solutions'
                    ] as $name => $anchor)
                    <li>
                        <a href="{{ route('home') }}#{{ $anchor }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">
                            {{ $name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Column 3: Platform & Trust (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-dark mb-4">Platform</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('features') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Features</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Pricing</a></li>
                    <li><a href="{{ route('contact') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Support</a></li>
                    <li><a href="{{ route('documentation.index') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Documentation</a></li>
                    <li><a href="{{ route('trust') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Trust Center</a></li>
                </ul>
            </div>

            {{-- Column 4: Legal & Compliance (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-dark mb-4">Legal</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('privacy') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Terms of Service</a></li>
                    <li><a href="{{ route('refunds') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Refund Policy</a></li>
                    <li><a href="{{ route('cookies') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Cookie Policy</a></li>
                    <li><a href="{{ route('retention') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Data Retention</a></li>
                    <li><a href="{{ route('aup') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Acceptable Use</a></li>
                </ul>
            </div>

            {{-- Column 5: Security & Privacy (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-dark mb-4">Privacy & Data</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('security') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Security Policy</a></li>
                    <li><a href="{{ route('ownership') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Data Ownership</a></li>
                    <li><a href="{{ route('student-privacy') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Student Privacy</a></li>
                    <li><a href="{{ route('communication-policy') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Consent Policy</a></li>
                    <li><a href="{{ route('dpa') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">DPA Agreement</a></li>
                    <li><a href="{{ route('subprocessors') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Subprocessors</a></li>
                    <li><a href="{{ route('legal') }}" class="text-xs text-dark/50 hover:text-accent transition-colors hover:pl-0.5 duration-200 block">Legal Notices</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Copyright Section --}}
        <div class="pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[11px] text-dark/40">
                &copy; {{ date('Y') }} <span class="text-accent font-semibold">Arzavo</span> by ARZAQ INSIGHTS. All rights reserved.
            </p>
            <p class="text-[11px] text-dark/40 flex items-center gap-1.5">
                <i class="fa-solid fa-lock text-emerald-600"></i> Hosted securely in AWS Mumbai region
            </p>
        </div>
    </div>
</footer>
