@extends('layouts.app')

@section('title', 'Contact Us - Arzavo Educational Management Platform')

@section('content')
@include('arzavo.partials.navbar')

<!-- Page Header -->
<section class="pt-32 pb-20 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-5">
        <div class="absolute top-[20%] left-[10%] w-[500px] h-[500px] bg-accent rounded-full blur-[100px] animate-float"></div>
    </div>
    <div class="container relative z-10 text-center">
        <h2 class="text-xs font-black uppercase tracking-[0.4em] text-accent mb-6 animate-fade-up">Direct Access</h2>
        <h1 class="text-5xl md:text-7xl font-black outfit-font tracking-tight mb-8 animate-fade-up stagger-1">
            Let's <span class="text-gradient-red">Connect.</span>
        </h1>
        <p class="text-xl text-secondary font-medium max-w-2xl mx-auto animate-fade-up stagger-2">
            Have questions about hosting your institution? Our success partners are ready to help you launch.
        </p>
    </div>
</section>

<!-- Contact Section (V3) -->
<section class="py-24 bg-white relative">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-16 items-start max-w-6xl mx-auto">
            
            <!-- Left: Contact Details & Info -->
            <div class="space-y-12">
                <div class="space-y-4">
                    <h3 class="text-3xl font-black outfit-font">Reach out to the team</h3>
                    <p class="text-lg text-secondary font-medium">
                        Whether you are a single tutor or a multi-branch university, 
                        Arzavo has the tools to scale your vision.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 gap-8">
                    <div class="p-8 glass-dark text-invert border-rounded-xl transition-all duration-500 hover-lift">
                        <i class="fa-solid fa-envelope text-accent text-3xl mb-6"></i>
                        <h4 class="text-xs font-black uppercase tracking-widest text-accent mb-2">Email Us</h4>
                        <p class="font-bold">hello@arzavo.com</p>
                    </div>
                    <div class="p-8 glass-dark text-invert border-rounded-xl transition-all duration-500 hover-lift">
                        <i class="fa-solid fa-phone text-accent text-3xl mb-6"></i>
                        <h4 class="text-xs font-black uppercase tracking-widest text-accent mb-2">Call Support</h4>
                        <p class="font-bold">+91 98765 43210</p>
                    </div>
                </div>

                <!-- Office / Location -->
                <div class="p-10 border-rounded-xl border-primary bg-tertiary/20">
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] text-tertiary mb-6">Global Headquarters</h4>
                    <p class="text-xl font-black leading-relaxed">
                        Innovation Tower, <br/>
                        Tech City, Digital State, <br/>
                        India - 400001
                    </p>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="bg-white p-10 md:p-12 border-rounded-xl shadow-2xl border-primary relative">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-accent/5 rounded-full pointer-events-none"></div>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-tertiary">First Name</label>
                            <input type="text" name="first_name" required placeholder="John" 
                                   class="w-full bg-tertiary/50 border-none px-5 py-4 border-rounded-lg text-sm font-bold focus:ring-2 focus:ring-accent outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-tertiary">Last Name</label>
                            <input type="text" name="last_name" required placeholder="Doe" 
                                   class="w-full bg-tertiary/50 border-none px-5 py-4 border-rounded-lg text-sm font-bold focus:ring-2 focus:ring-accent outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-tertiary">Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com" 
                               class="w-full bg-tertiary/50 border-none px-5 py-4 border-rounded-lg text-sm font-bold focus:ring-2 focus:ring-accent outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-tertiary">Institution Name</label>
                        <input type="text" name="institution" placeholder="Global University" 
                               class="w-full bg-tertiary/50 border-none px-5 py-4 border-rounded-lg text-sm font-bold focus:ring-2 focus:ring-accent outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-tertiary">Message</label>
                        <textarea name="message" required rows="4" placeholder="How can we help you?" 
                                  class="w-full bg-tertiary/50 border-none px-5 py-4 border-rounded-lg text-sm font-bold focus:ring-2 focus:ring-accent outline-none transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full py-5 bg-invert text-invert text-xs font-black uppercase tracking-widest border-rounded-lg hover:bg-accent hover-lift transition-all shadow-xl">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@include('arzavo.partials.footer')
@endsection
