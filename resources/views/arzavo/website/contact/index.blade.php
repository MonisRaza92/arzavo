@extends('layouts.app')

@section('title', 'Contact Us - Arzavo Educational Management Platform')

@section('content')
@include('arzavo.website.partials.navbar')

<!-- Page Header -->
<section class="pt-32 pb-20 bg-slate-950 relative overflow-hidden min-h-[40vh] flex flex-col justify-center">
    <!-- Animated Particles/Glows -->
    <div class="absolute inset-0 pointer-events-none opacity-30">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-accent/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-[100px] animate-[pulse_4s_ease-in-out_infinite]"></div>
    </div>
    
    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
        <div class="max-w-3xl mx-auto reveal-on-scroll">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 cursor-default">
                <i class="fa-solid fa-headset text-accent animate-bounce"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Direct Access</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8">
                Let's <span class="text-transparent bg-clip-text bg-linear-to-r from-accent via-accent-secondary to-blue-500">Connect.</span>
            </h1>
            <p class="text-xl text-slate-400 font-medium max-w-2xl mx-auto leading-relaxed">
                Have questions about hosting your institution? Our success partners are ready to help you launch.
            </p>
        </div>
    </div>
</section>

<!-- Contact Section (Split Layout) -->
<section class="py-24 bg-slate-900 relative">
    <!-- Subtle Background Grid -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0wIDBoNDB2NDBIMHoiIGZpbGw9Im5vbmUiLz4KPHBhdGggZD0iTTAgMGg0MHY0MEgwem0zOSAzOVYxaC0zOHYzOGgzOHoiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMSkiLz4KPC9zdmc+')] opacity-50 z-0"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="grid lg:grid-cols-5 gap-12 items-start max-w-7xl mx-auto">
            
            <!-- Left: Contact Details & Info (2/5 width) -->
            <div class="lg:col-span-2 space-y-8 reveal-on-scroll">
                
                <div class="glass-panel-dark p-8 md:p-10 rounded-[2rem] border border-white/5 relative overflow-hidden group">
                     <!-- Hover Glow Focus -->
                    <div class="absolute inset-0 bg-linear-to-br from-accent/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/5 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/10 group-hover:scale-110 group-hover:bg-accent group-hover:border-accent transition-all duration-500 shadow-xl">
                            <i class="fa-solid fa-envelope text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Email Us</h4>
                        <a href="mailto:hello@arzavo.com" class="text-2xl font-black text-white hover:text-accent transition-colors">hello@arzavo.com</a>
                        <p class="text-sm text-slate-500 font-medium mt-3">We typically reply within 2 hours during active business hours.</p>
                    </div>
                </div>

                <div class="glass-panel-dark p-8 md:p-10 rounded-[2rem] border border-white/5 relative overflow-hidden group">
                     <!-- Hover Glow Focus -->
                    <div class="absolute inset-0 bg-linear-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/5 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/10 group-hover:scale-110 group-hover:bg-blue-500 group-hover:border-blue-500 transition-all duration-500 shadow-xl">
                            <i class="fa-solid fa-phone text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Call Support</h4>
                        <a href="tel:+919876543210" class="text-2xl font-black text-white hover:text-blue-400 transition-colors">+91 98765 43210</a>
                        <p class="text-sm text-slate-500 font-medium mt-3">Priority support line available Mon-Fri, 9 AM to 6 PM IST.</p>
                    </div>
                </div>

                <!-- Office / Location -->
                <div class="p-10 rounded-[2rem] bg-white text-slate-900 relative overflow-hidden group">
                    <!-- Subtle pattern -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-accent/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative z-10">
                        <h4 class="text-xs font-black uppercase tracking-[0.3em] text-accent mb-6">Global Headquarters</h4>
                        <p class="text-xl font-black leading-relaxed mb-6 text-slate-800">
                            Innovation Tower, <br/>
                            Tech City, Digital State, <br/>
                            India - 400001
                        </p>
                        <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-accent transition-colors uppercase tracking-widest">
                            Get Directions <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Contact Form (3/5 width) -->
            <div class="lg:col-span-3 glass-panel-dark p-8 md:p-12 lg:p-16 rounded-[2.5rem] border border-white/10 relative shadow-2xl reveal-on-scroll stagger-1">
                <div class="absolute -top-10 -right-10 w-64 h-64 bg-accent/10 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px] pointer-events-none"></div>
                
                <h3 class="text-3xl font-black text-white mb-2 relative z-10">Send us a message</h3>
                <p class="text-slate-400 font-medium mb-10 relative z-10 hover:text-slate-300 transition-colors">Fill out the form below and our team will get back to you shortly.</p>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Floating Label Input -->
                        <div class="relative group">
                            <input type="text" name="first_name" id="first_name" required placeholder=" "
                                   class="block w-full px-5 pb-3 pt-7 text-sm text-white bg-white/5 border border-white/10 rounded-xl appearance-none focus:outline-none focus:ring-0 focus:border-accent focus:bg-white/10 transition-all peer">
                            <label for="first_name" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium cursor-text">First Name</label>
                        </div>
                        
                        <!-- Floating Label Input -->
                        <div class="relative group">
                            <input type="text" name="last_name" id="last_name" required placeholder=" "
                                   class="block w-full px-5 pb-3 pt-7 text-sm text-white bg-white/5 border border-white/10 rounded-xl appearance-none focus:outline-none focus:ring-0 focus:border-accent focus:bg-white/10 transition-all peer">
                            <label for="last_name" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium cursor-text">Last Name</label>
                        </div>
                    </div>

                    <!-- Floating Label Input -->
                    <div class="relative group">
                        <input type="email" name="email" id="email" required placeholder=" "
                               class="block w-full px-5 pb-3 pt-7 text-sm text-white bg-white/5 border border-white/10 rounded-xl appearance-none focus:outline-none focus:ring-0 focus:border-accent focus:bg-white/10 transition-all peer">
                        <label for="email" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium cursor-text">Email Address</label>
                    </div>

                    <!-- Floating Label Input -->
                    <div class="relative group">
                        <input type="text" name="institution" id="institution" placeholder=" "
                               class="block w-full px-5 pb-3 pt-7 text-sm text-white bg-white/5 border border-white/10 rounded-xl appearance-none focus:outline-none focus:ring-0 focus:border-accent focus:bg-white/10 transition-all peer">
                        <label for="institution" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium cursor-text">Institution Name (Optional)</label>
                    </div>

                    <!-- Floating Label Textarea -->
                    <div class="relative group">
                        <textarea name="message" id="message" required rows="5" placeholder=" "
                                  class="block w-full px-5 pb-3 pt-7 text-sm text-white bg-white/5 border border-white/10 rounded-xl appearance-none focus:outline-none focus:ring-0 focus:border-accent focus:bg-white/10 transition-all peer resize-none"></textarea>
                        <label for="message" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium cursor-text">How can we help you?</label>
                    </div>

                    <button type="submit" class="w-full py-5 bg-white text-slate-900 text-sm font-bold uppercase tracking-widest rounded-xl hover:bg-slate-200 hover:scale-[1.02] transition-all duration-300 shadow-[0_0_20px_rgba(255,255,255,0.1)] focus:ring-4 focus:ring-white/20 group/btn flex justify-center items-center gap-2">
                        <span>Send Message</span>
                        <i class="fa-solid fa-paper-plane group-hover/btn:-translate-y-1 group-hover/btn:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <p class="text-xs text-center text-slate-500 font-medium mt-6">
                        By submitting this form, you agree to our <a href="#" class="text-slate-400 underline hover:text-white transition-colors">Privacy Policy</a>.
                    </p>
                </form>
            </div>

        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection
