{{-- =========================
WHY ARZAVO
========================= --}}
<section id="why-arzavo" class="relative py-20 overflow-hidden"
    style="background:linear-gradient(180deg,#ffffff 0%,#fffaf8 45%,#ffffff 100%);">

    {{-- Background --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 right-[-120px] w-[500px] h-[500px] rounded-full opacity-20"
            style="background:radial-gradient(circle,rgba(146,0,0,.12)0%,transparent 70%);">
        </div>
        <div class="absolute -bottom-32 left-[-120px] w-[420px] h-[420px] rounded-full opacity-20"
            style="background:radial-gradient(circle,rgba(197,132,0,.12)0%,transparent 70%);">
        </div>
    </div>

    <div class="container relative z-10">

        {{-- ===== Main hero block ===== --}}
        <div class="flex flex-col-reverse lg:flex-row items-center gap-16">
            <div class="relative w-full lg:w-1/2">
                <img src="{{ asset('images/website/dashboard.png') }}" alt="Features Image"
                    class="relative z-10 object-cover w-full rounded-lg border shadow-xl">
                {{-- floating badge on image --}}
                <div
                    class="absolute -bottom-6 -right-4 z-20 bg-white rounded-xl shadow-lg px-5 py-3 border hidden md:flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-dark leading-none">99.9% Uptime</p>
                        <p class="text-xs text-dark/60 mt-1">Trusted &amp; Reliable</p>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/2 w-full">
                <h2 class="text-4xl md:text-5xl font-semibold leading-tight text-dark mb-6">
                    The Complete Operating System for
                    <span class="text-accent">Educational Institutions.</span>
                </h2>
                <p class="text-lg text-dark/70 leading-8 mb-5">
                    Managing an educational institution involves handling multiple departments, operations, and
                    stakeholders every single day. Arzavo brings everything together in one unified platform — from
                    student admissions and academic records to fee management, attendance tracking, examinations,
                    communications, staff management, and online learning.
                </p>

                <div class="flex flex-wrap gap-4">
                    <x-button url="{{ route('register.form') }}" padding="px-6 py-3">
                        Get Started
                        <i class="fa-solid fa-arrow-right -rotate-45"></i>
                    </x-button>

                    <x-button url="#features" variant="accent" :loading="false" padding="px-6 py-3">
                        Explore Features
                        <i class="fa-solid fa-chevron-right"></i>
                    </x-button>
                </div>
            </div>
        </div>
        {{-- ===== Stats strip ===== --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-26">
            @php
                $stats = [
                    ['value' => '100+', 'label' => 'Institutions Onboarded'],
                    ['value' => '10K+', 'label' => 'Students Managed'],
                    ['value' => '99.9%', 'label' => 'Platform Uptime'],
                    ['value' => '24/7', 'label' => 'Dedicated Support'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="text-center p-6 rounded-lg bg-accent/10">
                    <h3 class="text-3xl md:text-4xl font-bold text-accent mb-1">{{ $stat['value'] }}</h3>
                    <p class="text-sm text-dark/60">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>