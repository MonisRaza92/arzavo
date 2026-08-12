@extends('layouts.app')
@section('title', 'About Arzavo by Arzaq Insights - Our Story & Mission')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero --}}
<section class="relative pt-32 pb-20 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Our Story</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Building the future of
                <span class="text-accent">education infrastructure.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed mb-8 animate-fade-in-up" style="animation-delay:.1s;">
                We believe every educational institution — from a 10-student coaching center to a 5,000-student school — deserves enterprise-grade software without the enterprise price tag.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 animate-fade-in-up" style="animation-delay:.2s;">
                <x-button url="{{ route('register.form') }}" variant="accent" padding="px-7 py-3">
                    Start Free Trial <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                </x-button>
                <x-button url="#story" padding="px-7 py-3">
                    Read Our Story
                </x-button>
            </div>
        </div>
    </div>
</section>

{{-- Stats Bar --}}
<section class="relative py-16 overflow-hidden" style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php $stats = [
                ['value' => '500+', 'label' => 'Institutes Active', 'icon' => 'fa-building-columns'],
                ['value' => '1M+', 'label' => 'Students Served', 'icon' => 'fa-user-graduate'],
                ['value' => '99.9%', 'label' => 'Platform Uptime', 'icon' => 'fa-server'],
                ['value' => '24/7', 'label' => 'Expert Support', 'icon' => 'fa-headset'],
            ]; @endphp
            @foreach($stats as $stat)
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-4">
                    <i class="fa-solid {{ $stat['icon'] }} text-sm"></i>
                </div>
                <div class="text-2xl font-semibold text-dark mb-1">{{ $stat['value'] }}</div>
                <div class="text-xs text-dark/50 font-medium uppercase tracking-wider">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Origin Story --}}
<section id="story" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #fff 0%, #fffbf8 50%, #fff 100%);">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-16 items-start">

            {{-- Text --}}
            <div >
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Why We Exist</p>
                <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-6 leading-tight tracking-tight">Born from a real problem.</h2>
                <div class="space-y-4">
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent shrink-0 mt-0.5">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-dark mb-2">The Observation</h4>
                                <p class="text-sm text-dark/60 leading-relaxed">
                                    Founded in 2024 by <strong class="text-dark">Arzaq Insights</strong>, Arzavo emerged from a simple observation: educational institutions were struggling with outdated, inflexible platforms that couldn't adapt to their unique workflows. Schools and coaching centers were forced to compromise their vision due to technological constraints.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-accent-secondary/10 flex items-center justify-center text-accent-secondary shrink-0 mt-0.5">
                                <i class="fa-solid fa-lightbulb text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-dark mb-2">The Solution</h4>
                                <p class="text-sm text-dark/60 leading-relaxed">
                                    So we built what we wished existed: a modular, white-label educational OS that gives institutions true autonomy. A platform where the technology works for you, not the other way around.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 shrink-0 mt-0.5">
                                <i class="fa-solid fa-chart-line text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-dark mb-2">The Impact</h4>
                                <p class="text-sm text-dark/60 leading-relaxed">
                                    Today, Arzavo powers hundreds of institutions across India, enabling them to deliver world-class education in their own digital space — with their own branding, their own rules, and their own identity.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="space-y-0">
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-6">Timeline</p>
                @php $timeline = [
                    ['year' => '2024 Q1', 'label' => 'Foundation', 'text' => 'Arzaq Insights founded Arzavo with a vision to make institutional-grade software accessible to every school and coaching center.'],
                    ['year' => '2024 Q3', 'label' => 'First 50 Tenants', 'text' => 'Rapid adoption by coaching institutes, leading to our first major product release with LMS integration.'],
                    ['year' => '2025 Q1', 'label' => 'Scale & Growth', 'text' => 'Crossed 500 active tenants and 50,000 students. Launched digital academy features and payment integrations.'],
                    ['year' => 'Now', 'label' => 'Enterprise Ready', 'text' => 'Serving schools, coaching institutes, and digital academies across India with a world-class feature set.'],
                ]; @endphp

                @foreach($timeline as $i => $item)
                <div class="flex gap-5">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-lg border border-gray-200 bg-white text-accent text-xs font-semibold flex items-center justify-center shrink-0">{{ $i + 1 }}</div>
                        @if(!$loop->last)<div class="w-px flex-1 bg-gray-200 my-1"></div>@endif
                    </div>
                    <div class="pb-8">
                        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">{{ $item['year'] }} · {{ $item['label'] }}</p>
                        <p class="text-sm text-dark/60 leading-relaxed">{{ $item['text'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container">
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Our DNA</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">The principles we build on.</h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Every line of code, every design decision, and every customer interaction is guided by these values.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php $values = [
                ['icon' => 'fa-user-graduate', 'title' => 'Student-Centric', 'text' => 'Every feature is architected with the ultimate goal of improving student focus, engagement, and learning outcomes.'],
                ['icon' => 'fa-shield-halved', 'title' => 'Security First', 'text' => 'Zero compromises on data security. Complete tenant isolation, end-to-end encryption, and rigorous compliance.'],
                ['icon' => 'fa-lightbulb', 'title' => 'Relentless Innovation', 'text' => 'We push the boundaries of what\'s possible in EdTech, consistently shipping updates that keep our partners ahead.'],
                ['icon' => 'fa-handshake', 'title' => 'True Partnership', 'text' => 'We are an extension of your IT team. Your growth is our growth. We provide unparalleled 24/7 concierge support.'],
            ]; @endphp
            @foreach($values as $value)
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-5">
                    <i class="fa-solid {{ $value['icon'] }} text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-dark mb-2">{{ $value['title'] }}</h3>
                <p class="text-sm text-dark/60 leading-relaxed">{{ $value['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Team --}}
<section id="team" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #fff 0%, #fffbf8 60%, #fff 100%);">
    <div class="container">
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Leadership</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">Meet the minds behind Arzavo.</h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                A team of visionary educators, software architects, and design purists obsessed with building the future of education.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php $team = [
                ['name' => 'Rajesh Kumar', 'role' => 'Founder & CEO', 'initials' => 'RK', 'color' => '#920000', 'bio' => '15+ years engineering scalable systems. Former lead architect at enterprise tech giants. Obsessed with democratizing institutional software.'],
                ['name' => 'Priya Sharma', 'role' => 'Co-Founder & CTO', 'initials' => 'PS', 'color' => '#c58400', 'bio' => 'Full-stack polyglot and cloud infrastructure expert. Designed high-availability systems managing millions of concurrent connections.'],
                ['name' => 'Dr. Amit Patel', 'role' => 'VP of Pedagogy', 'initials' => 'AP', 'color' => '#333', 'bio' => 'Ph.D. in Cognitive Science & EdTech. 20+ years researching how technology interplays with human learning capacity. Former university dean.'],
            ]; @endphp
            @foreach($team as $member)
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-12 h-12 rounded-full text-white flex items-center justify-center text-sm font-semibold"
                         style="background: {{ $member['color'] }};">{{ $member['initials'] }}</div>
                    <div>
                        <h3 class="text-sm font-semibold text-dark">{{ $member['name'] }}</h3>
                        <p class="text-xs text-accent font-medium">{{ $member['role'] }}</p>
                    </div>
                </div>
                <p class="text-sm text-dark/60 leading-relaxed mb-5">{{ $member['bio'] }}</p>
                <div class="flex gap-2">
                    <a href="#" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-colors">
                        <i class="fa-brands fa-linkedin-in text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-dark/30 hover:text-accent hover:border-accent/30 transition-colors">
                        <i class="fa-brands fa-x-twitter text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container">
        <div class="rounded-lg p-12 md:p-16 bg-accent text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] rounded-full pointer-events-none opacity-15"
                 style="background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, transparent 65%); transform: translate(30%, -30%);"></div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <div class="w-14 h-14 mx-auto rounded-xl bg-white/15 flex items-center justify-center mb-6">
                    <i class="fa-solid fa-users text-white text-xl"></i>
                </div>
                <h2 class="text-3xl md:text-5xl font-semibold text-white mb-5 leading-tight tracking-tight">
                    Help us build the future of learning.
                </h2>
                <p class="text-white/75 text-lg leading-relaxed mb-10">
                    We're seeking elite engineers, designers, and educators to join our remote-first team.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#"
                       class="px-8 py-3.5 bg-white text-accent font-semibold rounded text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                        View Open Positions <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="px-8 py-3.5 font-semibold rounded text-sm flex items-center justify-center gap-2 transition-colors text-white"
                       style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                        <i class="fa-regular fa-envelope text-xs"></i> Contact Us
                    </a>
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
