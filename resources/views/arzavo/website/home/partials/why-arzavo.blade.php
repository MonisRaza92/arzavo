{{-- Why Arzavo Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="why-arzavo" class="py-20 bg-secondary">
    <div class="container">

        {{-- Main Feature Spotlight --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            {{-- Left Visual Showcase --}}
            <div class="lg:col-span-6 h-full">
                <div class="relative h-full">
                    <img src="{{ asset('images/website/teacher-smart-class.jpg') }}"
                         alt="Arzavo Comprehensive Dashboard"
                         class="w-full h-full rounded-lg object-cover">

                    {{-- Floating Verification Pill --}}
                    <!-- <div class="mt-3 flex items-center justify-between bg-secondary rounded-lg px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-success"></span>
                            <div>
                                <p class="text-xs font-semibold text-theme-text">99.9% High Availability</p>
                                <p class="text-[11px] text-theme-text-muted font-normal">Automated failover & zero data loss guarantee</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-success-bg text-success text-[10px] font-normal">
                            System Live
                        </span>
                    </div> -->
                </div>
            </div>

            {{-- Right Content & Pillars --}}
            <div class="lg:col-span-6 flex flex-col items-start">
                <!-- <span class="px-3 py-1 rounded-full bg-primary text-theme-text text-[11px] font-normal mb-4">
                    Enterprise Operating System
                </span> -->

                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight mb-5">
                    The Complete Operating System for <span class="text-brand">Modern</span> Educational Institutions.
                </h2>

                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed mb-6">
                    Managing an educational institution involves juggling multiple departments, branches, and stakeholders every day. Arzavo unifies every operational vector in one secure platform — from student admissions and academic registers to automated GST invoicing, smart biometric attendance, scheduled examinations, and complete LMS distribution.
                </p>

                {{-- Pillar Badges --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full mb-8">
                    <div class="bg-primary rounded-lg p-3.5 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs shrink-0 font-semibold">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-theme-text">Unified Multi-Campus</h4>
                            <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">Manage single or multi-branch academies with ease.</p>
                        </div>
                    </div>

                    <div class="bg-primary rounded-lg p-3.5 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs shrink-0 font-semibold">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-theme-text">Zero-Trust Isolation</h4>
                            <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">Every campus gets its own isolated database schema.</p>
                        </div>
                    </div>
                </div>

                {{-- CTAs --}}
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('arzaq.redirect') }}"
                       class="px-6 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right -rotate-45 text-[11px]"></i>
                    </a>
                    <a href="#features"
                       class="px-6 py-3 bg-primary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                        <span>Explore Features</span>
                        <i class="fa-solid fa-chevron-right text-[11px]"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- Metric Banner Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16">
            @php
                $stats = [
                    ['value' => '100+', 'label' => 'Institutions Onboarded', 'icon' => 'fa-school'],
                    ['value' => '10K+', 'label' => 'Learners Enrolled', 'icon' => 'fa-graduation-cap'],
                    ['value' => '99.9%', 'label' => 'Platform Uptime SLA', 'icon' => 'fa-server'],
                    ['value' => '24/7', 'label' => 'Dedicated Human Support', 'icon' => 'fa-headset'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="bg-primary rounded-lg p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl md:text-3xl font-semibold text-theme-text">{{ $stat['value'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-xs">
                            <i class="fa-solid {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                    <p class="text-xs text-theme-text-muted font-normal">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

    </div>
</section>