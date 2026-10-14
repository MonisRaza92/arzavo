@extends('layouts.arzavo.website')

@section('title', 'Contact Support & Institutional Sales • Arzavo')
@section('description', 'Reach out to the Arzavo engineering and sales team for institutional demos, database migration assistance, enterprise SLA pricing, and campus onboarding.')

@section('content')
@include('arzavo.website.partials.navbar')

<main class="pt-24 min-h-screen bg-secondary">

    {{-- Contact Hero Header --}}
    <section class="py-16 md:py-20 border-b border-secondary">
        <div class="container text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold mb-6">
                <i class="fa-solid fa-headset text-[10px]"></i>
                <span>Institutional Assistance</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-semibold text-theme-text tracking-tight mb-5 leading-tight">
                Let’s Scale Your <span class="text-brand">Academy</span> Together.
            </h1>

            <p class="text-sm md:text-base text-theme-text-muted leading-relaxed max-w-2xl mx-auto font-normal">
                Whether you need a personalized walkthrough of the Multi-Tenant ERP, assistance with legacy database migration, or custom multi-branch pricing, our dedicated team is here to help.
            </p>
        </div>
    </section>

    {{-- Contact Main Grid (Details Left, Form Right) --}}
    <section class="py-16 md:py-24 bg-secondary">
        <div class="container max-w-6xl">

            @if(session('success'))
                <div class="mb-10 p-4 rounded-xl bg-success/10 border border-success/30 text-success text-xs font-medium flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                {{-- Left: Contact Information Cards (5 cols) --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- Info Card: Email --}}
                    <div class="bg-primary p-6 rounded-xl border border-secondary flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-theme-text-muted mb-1">Direct Support Email</h3>
                            <a href="mailto:support@arzavo.in" class="text-sm font-semibold text-theme-text hover:text-brand transition block">
                                support@arzavo.in
                            </a>
                            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">Guaranteed response within 24 business hours</p>
                        </div>
                    </div>

                    {{-- Info Card: Phone & WhatsApp --}}
                    <div class="bg-primary p-6 rounded-xl border border-secondary flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-theme-text-muted mb-1">Direct Helpline</h3>
                            <a href="tel:+919876543210" class="text-sm font-semibold text-theme-text hover:text-brand transition block">
                                +91 98765 43210
                            </a>
                            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">Mon – Sat, 9:00 AM – 7:00 PM IST</p>
                        </div>
                    </div>

                    {{-- Info Card: Registered Address --}}
                    <div class="bg-primary p-6 rounded-xl border border-secondary flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-theme-text-muted mb-1">Corporate HQ</h3>
                            <p class="text-xs font-medium text-theme-text leading-relaxed">
                                ARZAQ INSIGHTS<br>
                                208/10 Musapur, Sandila, Hardoi,<br>
                                Uttar Pradesh – 241204, India
                            </p>
                            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">Hosted securely in AWS Mumbai Data Center</p>
                        </div>
                    </div>

                    {{-- Self-Service Documentation Card --}}
                    <div class="bg-gradient-to-br from-brand/10 to-primary p-6 rounded-xl border border-brand/20">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fa-solid fa-book-open text-brand text-sm"></i>
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-theme-text">Self-Service Documentation</h3>
                        </div>
                        <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-4">
                            Looking for quick answers? Browse our 25+ step-by-step guides covering website building, custom domains, exams, and fee setups.
                        </p>
                        <a href="{{ (request()->secure() ? 'https://' : 'http://') . 'docs.' . config('app.domain') }}"
                           class="inline-flex items-center gap-1.5 text-xs text-brand font-semibold hover:underline">
                            <span>Browse Platform Documentation</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                </div>

                {{-- Right: Contact Form (7 cols) --}}
                <div class="lg:col-span-7 bg-primary p-8 md:p-10 rounded-xl border border-secondary shadow-sm">
                    <div class="mb-8">
                        <h2 class="text-xl md:text-2xl font-semibold text-theme-text mb-2">Send us an Institutional Inquiry</h2>
                        <p class="text-xs text-theme-text-muted font-normal">Fill out the form below and an academy deployment consultant will reach out.</p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-xs font-medium text-theme-text mb-1.5">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="first_name" name="first_name" required
                                       value="{{ old('first_name') }}"
                                       placeholder="Jane"
                                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">
                                @error('first_name')
                                    <p class="text-danger text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-xs font-medium text-theme-text mb-1.5">Last Name <span class="text-danger">*</span></label>
                                <input type="text" id="last_name" name="last_name" required
                                       value="{{ old('last_name') }}"
                                       placeholder="Doe"
                                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">
                                @error('last_name')
                                    <p class="text-danger text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-medium text-theme-text mb-1.5">Work Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" required
                                       value="{{ old('email') }}"
                                       placeholder="director@myschool.edu"
                                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">
                                @error('email')
                                    <p class="text-danger text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-medium text-theme-text mb-1.5">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="+91 98765 00000"
                                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">
                                @error('phone')
                                    <p class="text-danger text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="institution" class="block text-xs font-medium text-theme-text mb-1.5">Academy / School Name</label>
                                <input type="text" id="institution" name="institution"
                                       value="{{ old('institution') }}"
                                       placeholder="St. Xavier's International School"
                                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">
                            </div>

                            <div>
                                <label for="students_count" class="block text-xs font-medium text-theme-text mb-1.5">Current Active Students</label>
                                <select id="students_count" name="students_count"
                                        class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">
                                    <option value="1-100">1 – 100 students (Tutor / Micro Batch)</option>
                                    <option value="100-500">100 – 500 students (Coaching Center)</option>
                                    <option value="500-2000" selected>500 – 2,000 students (Independent School)</option>
                                    <option value="2000-5000">2,000 – 5,000 students (College / Academy)</option>
                                    <option value="5000+">5,000+ students (Franchise / University)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-medium text-theme-text mb-1.5">How can our team assist your institution? <span class="text-danger">*</span></label>
                            <textarea id="message" name="message" rows="4" required
                                      placeholder="Tell us about your campus requirements, current software challenges, or desired timeline..."
                                      class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs p-4 rounded-lg border border-secondary focus:outline-none focus:border-brand transition">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-danger text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full sm:w-auto px-8 py-3.5 bg-brand text-primary rounded-full text-xs font-semibold hover:opacity-90 transition flex items-center justify-center gap-2">
                                <span>Submit Inquiry</span>
                                <i class="fa-solid fa-paper-plane text-[10px]"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

</main>

@include('arzavo.website.partials.footer')
@endsection
