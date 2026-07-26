@php
    $logo = render_logo();
    $tenantName = tenant_name();
    $desc = $section->description ?? 'Empowering learners with high-quality educational courses and expert faculty.';
    $copy = $section->copyright_text ?? 'All Rights Reserved.';
@endphp

<footer {!! $section->attributes() !!} class="bg-slate-900 text-slate-300 py-16 border-t border-slate-800 {{ $section->visibility }}">
    <div class="arz-container">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <!-- Brand Column -->
            <div class="col-span-1 md:col-span-1">
                <a href="{{ route('tenant.home') }}" class="inline-block mb-4">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $tenantName }}" class="aurora-logo-img">
                    @else
                        <span class="font-extrabold text-2xl tracking-tight text-white">{{ $tenantName }}</span>
                    @endif
                </a>
                <p class="text-sm text-slate-400 leading-relaxed">
                    {{ $desc }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold text-base mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ route('tenant.home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('tenant.courses') }}" class="hover:text-white transition">Courses</a></li>
                    <li><a href="{{ route('tenant.about') }}" class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('tenant.blogs') }}" class="hover:text-white transition">Blog Updates</a></li>
                </ul>
            </div>

            <!-- Academic Pages -->
            <div>
                <h4 class="text-white font-bold text-base mb-4">Student & Legal</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ route('tenant.login') }}" class="hover:text-white transition">Student Portal</a></li>
                    <li><a href="{{ route('tenant.privacy-policy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('tenant.terms-conditions') }}" class="hover:text-white transition">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Contact & Social -->
            <div>
                <h4 class="text-white font-bold text-base mb-4">Connect With Us</h4>
                <p class="text-sm text-slate-400 mb-4">Stay updated with our latest course launches and academic announcements.</p>
                <div class="flex items-center gap-3 text-slate-400 text-lg">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
            © {{ date('Y') }} {{ $tenantName }}. {{ $copy }}
        </div>
    </div>
</footer>
