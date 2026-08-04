@extends('layouts.app')
@section('title', 'Contact Us - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Get In Touch</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Let's talk about your
                <span class="text-accent">institute.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Have questions about hosting your institution? Our success partners are ready to help you launch.
            </p>
        </div>
    </div>
</section>

{{-- Contact Content --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container">
        <div class="grid lg:grid-cols-5 gap-8 items-start">

            {{-- Left: Contact Info --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Email --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-dark/40 mb-1">Email Us</p>
                            <a href="mailto:support@arzavo.com" class="text-lg font-semibold text-dark hover:text-accent transition-colors">support@arzavo.com</a>
                            <p class="text-sm text-dark/50 mt-1">We typically reply within 2 hours during business hours.</p>
                        </div>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-accent-secondary/10 flex items-center justify-center text-accent-secondary shrink-0">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-dark/40 mb-1">Call Support</p>
                            <a href="tel:+918090492602" class="text-lg font-semibold text-dark hover:text-accent transition-colors">+91 80904 92602</a>
                            <p class="text-sm text-dark/50 mt-1">Priority line available Mon-Fri, 9 AM to 6 PM IST.</p>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-600 shrink-0">
                            <i class="fa-solid fa-location-dot text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-dark/40 mb-1">Headquarters</p>
                            <p class="text-sm font-semibold text-dark leading-relaxed">
                                ARZAQ INSIGHTS<br/>208/10 Musapur, Sandila,<br/>Hardoi, Uttar Pradesh – 241204
                            </p>
                            <a href="#" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 mt-2 hover:gap-3 transition-all duration-300">
                                Get Directions <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Social --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <p class="text-xs font-semibold uppercase tracking-widest text-dark/40 mb-4">Follow Us</p>
                    <div class="flex gap-2">
                        @php $socials = [
                            ['icon' => 'fa-brands fa-x-twitter', 'url' => '#'],
                            ['icon' => 'fa-brands fa-linkedin-in', 'url' => '#'],
                            ['icon' => 'fa-brands fa-instagram', 'url' => '#'],
                            ['icon' => 'fa-brands fa-youtube', 'url' => '#'],
                        ]; @endphp
                        @foreach($socials as $social)
                        <a href="{{ $social['url'] }}" class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-colors">
                            <i class="{{ $social['icon'] }} text-sm"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: Form --}}
            <div class="lg:col-span-3">
                <div class="rounded-lg border border-gray-200 bg-white p-8 md:p-10">
                    <h3 class="text-xl font-semibold text-dark mb-2">Send us a message</h3>
                    <p class="text-sm text-dark/50 mb-8">Fill out the form and our team will get back to you shortly.</p>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-dark/60 mb-1.5">First Name</label>
                                <input type="text" name="first_name" required
                                       class="w-full px-4 py-3 rounded-lg bg-white text-sm text-dark placeholder-dark/30 outline-none transition-all focus:ring-2 focus:ring-accent/20 border border-gray-200"
                                       placeholder="Enter first name">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-dark/60 mb-1.5">Last Name</label>
                                <input type="text" name="last_name" required
                                       class="w-full px-4 py-3 rounded-lg bg-white text-sm text-dark placeholder-dark/30 outline-none transition-all focus:ring-2 focus:ring-accent/20 border border-gray-200"
                                       placeholder="Enter last name">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-dark/60 mb-1.5">Email Address</label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 rounded-lg bg-white text-sm text-dark placeholder-dark/30 outline-none transition-all focus:ring-2 focus:ring-accent/20 border border-gray-200"
                                   placeholder="you@example.com">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-dark/60 mb-1.5">Institution Name <span class="text-dark/30">(Optional)</span></label>
                            <input type="text" name="institution"
                                   class="w-full px-4 py-3 rounded-lg bg-white text-sm text-dark placeholder-dark/30 outline-none transition-all focus:ring-2 focus:ring-accent/20 border border-gray-200"
                                   placeholder="Your institution">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-dark/60 mb-1.5">How can we help you?</label>
                            <textarea name="message" required rows="5"
                                      class="w-full px-4 py-3 rounded-lg bg-white text-sm text-dark placeholder-dark/30 outline-none transition-all focus:ring-2 focus:ring-accent/20 resize-none border border-gray-200"
                                      placeholder="Tell us about your requirements..."></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3.5 bg-accent text-white text-sm font-semibold rounded-lg flex items-center justify-center gap-2 hover:opacity-90 transition-opacity cursor-pointer">
                            Send Message
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                        <p class="text-xs text-center text-dark/40">
                            By submitting, you agree to our <a href="#" class="text-dark/50 underline hover:text-accent transition-colors">Privacy Policy</a>.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection

<style>
@keyframes fade-in-down { from{opacity:0;transform:translateY(-12px);}to{opacity:1;transform:translateY(0);} }
@keyframes fade-in-up { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
.animate-fade-in-down { animation: fade-in-down .6s ease-out both; }
.animate-fade-in-up { animation: fade-in-up .6s ease-out both; }
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>
