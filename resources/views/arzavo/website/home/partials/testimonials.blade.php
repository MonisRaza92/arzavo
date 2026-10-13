{{-- Testimonials Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="testimonials" class="py-20 bg-secondary overflow-hidden">
    <div class="container mb-8">

        {{-- Section Header (Split Layout: Title Left, Description Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-12">

            {{-- Left: Main Heading --}}
            <div class="lg:col-span-7">
                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight">
                    Trusted by Educational <br class="hidden sm:inline">
                    <span class="text-brand">Founders</span> & Academy Leaders.
                </h2>
            </div>

            {{-- Right: Subtitle --}}
            <div class="lg:col-span-5 flex flex-col justify-end">
                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                    Hear from institute directors, school principals, and competitive coaching founders who eliminated fee leakages, streamlined daily faculty workflows, and scaled student enrollments using Arzavo's unified educational platform.
                </p>
            </div>

        </div>

        {{-- Spotlight Featured Case Study --}}
        <div class="bg-primary rounded-lg p-6 sm:p-8 lg:p-10 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                {{-- Left Image Visual --}}
                <div class="lg:col-span-6 h-full">
                    <div class="relative bg-secondary rounded-lg overflow-hidden h-full min-h-[260px] sm:min-h-[320px]">
                        <img
                            src="{{ asset('images/website/students-classroom.jpg') }}"
                            alt="Institutional Academic Success Story"
                            class="w-full h-full object-cover rounded-lg"
                            loading="lazy"
                        >

                        <div class="absolute bottom-4 left-4 right-4 sm:right-auto inline-flex items-center gap-2.5 bg-primary/95 backdrop-blur-sm px-3.5 py-2 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-success"></span>
                            <span class="text-xs font-normal text-theme-text">Excellence Academy · 3 Campuses</span>
                            <span class="text-[11px] font-normal text-theme-text-muted hidden sm:inline">· 2,400+ Learners</span>
                        </div>
                    </div>
                </div>

                {{-- Right Director Quote & Metrics --}}
                <div class="lg:col-span-6 flex flex-col justify-between">
                    <div>
                        {{-- Star Rating --}}
                        <div class="flex items-center gap-1 text-warning text-xs mb-4">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="text-theme-text font-semibold ml-2 text-xs">5.0 / 5.0</span>
                        </div>

                        <p class="text-sm sm:text-base text-theme-text font-normal leading-relaxed mb-6">
                            "Arzavo completely transformed our test prep center. Managing multiple batches, schedules, and fee collections across our three branches used to take hours of manual work. Now, attendance, fees, and exam grading run entirely on autopilot."
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-secondary rounded-lg p-3">
                                <span class="text-lg font-semibold text-theme-text">80%</span>
                                <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">Faster Fee Reconciliation</p>
                            </div>
                            <div class="bg-secondary rounded-lg p-3">
                                <span class="text-lg font-semibold text-theme-text">100%</span>
                                <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">Parent Visibility & Trust</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <div class="w-10 h-10 rounded-full bg-secondary text-brand flex items-center justify-center font-semibold text-xs">
                                RS
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-theme-text">Rajesh Sharma</h4>
                                <p class="text-[11px] text-theme-text-muted font-normal">Director, Excellence Academy</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Seamless Testimonial Scroll Track --}}
    <div class="relative overflow-hidden w-full">
        <div class="flex gap-4 px-6 animate-scroll-x hover:[animation-play-state:paused]">
            @php
            $testimonials = [
                [
                    'text' => 'The white-labeled custom domain setup is seamless. Our students get their own login dashboard branded with our logo and custom colors. Plus, automated WhatsApp reminders reduced overdue fees by 80%.',
                    'name' => 'Priya Kapoor',
                    'role' => 'Principal, Green Valley School',
                    'initials' => 'PK',
                ],
                [
                    'text' => 'We migrated our custom portal database to Arzavo. The platform’s physical database isolation, granular staff permissions, and automated nightly backups gave us complete peace of mind.',
                    'name' => 'Amit Mehta',
                    'role' => 'IT Head, Sunrise Institute',
                    'initials' => 'AM',
                ],
                [
                    'text' => 'As a solo coaching center owner, I was managing everything on WhatsApp and Google Sheets. Arzavo gave me a professional setup in minutes. My students have their own portal and I track everything from one dashboard.',
                    'name' => 'Sneha Verma',
                    'role' => 'Founder, LearnSphere Coaching',
                    'initials' => 'SV',
                ],
                [
                    'text' => 'The examination engine is top notch. We create MCQ tests, auto-grade them, and students get instant result analytics with subject-wise breakdowns. Parents love the transparency.',
                    'name' => 'Dr. Vikram Singh',
                    'role' => 'Director, Bright Future Academy',
                    'initials' => 'VS',
                ],
                [
                    'text' => 'I run a digital academy with recorded video courses. Arzavo’s watermark DRM protection stops piracy, and integrated payment processing lets us collect student fees directly with zero revenue cuts.',
                    'name' => 'Kavita Joshi',
                    'role' => 'CEO, SkillBridge Online',
                    'initials' => 'KJ',
                ],
                [
                    'text' => 'Managing 1,200 students across 4 branches was a nightmare before Arzavo. Now, automated attendance WhatsApp messages go to parents instantly when students scan their biometric card.',
                    'name' => 'Mohd. Zeeshan',
                    'role' => 'Academic Dean, Apex Scholars',
                    'initials' => 'MZ',
                ],
            ];
            @endphp

            @foreach(array_merge($testimonials, $testimonials) as $t)
            <div class="bg-primary rounded-lg p-6 min-w-[320px] max-w-[360px] flex flex-col justify-between shrink-0">
                <div>
                    <div class="flex items-center gap-1 text-warning text-[10px] mb-3">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        "{{ $t['text'] }}"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center font-semibold text-xs shrink-0">
                        {{ $t['initials'] }}
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-theme-text">{{ $t['name'] }}</h4>
                        <p class="text-[11px] text-theme-text-muted font-normal">{{ $t['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
