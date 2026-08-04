{{-- Testimonials Section --}}
<section id="testimonials" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #fffbf8 60%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Customer Success</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Trusted by leading academies and schools.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Hear from institute owners and educators who transformed their operations with Arzavo.
            </p>
        </div>
    </div>

    {{-- Horizontal Scroll Carousel --}}
    <div class="relative overflow-hidden">
        <div class="testimonial-track flex gap-6 px-6 animate-scroll-x">
            @php
            $testimonials = [
                [
                    'text' => 'Arzavo completely transformed our test prep center. Managing multiple batches, schedules, and fee collections across our three branches used to take up hours of manual work. Now, it runs entirely on autopilot.',
                    'name' => 'Rajesh Sharma',
                    'role' => 'Director, Excellence Academy',
                    'initials' => 'RS',
                    'color' => '#920000',
                ],
                [
                    'text' => 'The white-labeled LMS domain setup is incredible. Our students get their own dashboard branded with our logo and primary colors. Plus, the automated WhatsApp fee reminders have dropped pending fees by 80%.',
                    'name' => 'Priya Kapoor',
                    'role' => 'Principal, Green Valley School',
                    'initials' => 'PK',
                    'color' => '#c58400',
                ],
                [
                    'text' => 'We migrated our custom PHP portal database to Arzavo. The platform\'s security architecture, granular staff permissions, and seamless Zoom class integration gave us total confidence to run operations online.',
                    'name' => 'Amit Mehta',
                    'role' => 'IT Head, Sunrise Institute',
                    'initials' => 'AM',
                    'color' => '#333333',
                ],
                [
                    'text' => 'As a solo coaching center owner, I was managing everything on WhatsApp and Google Sheets. Arzavo gave me a professional setup in 2 hours. My students now have their own login portal and I track everything from one dashboard.',
                    'name' => 'Sneha Verma',
                    'role' => 'Founder, LearnSphere Coaching',
                    'initials' => 'SV',
                    'color' => '#920000',
                ],
                [
                    'text' => 'The exam engine is a game-changer. We create MCQ tests, auto-grade them, and students get instant result cards with subject-wise breakdown. Parents love the transparency. Our re-enrollment rate went up by 40%.',
                    'name' => 'Dr. Vikram Singh',
                    'role' => 'Director, Bright Future Academy',
                    'initials' => 'VS',
                    'color' => '#c58400',
                ],
                [
                    'text' => 'I run a digital academy selling recorded courses. Arzavo\'s video protection stops piracy, and the built-in payment gateway means I don\'t need any third-party tools. Revenue doubled in 6 months.',
                    'name' => 'Kavita Joshi',
                    'role' => 'CEO, SkillBridge Online',
                    'initials' => 'KJ',
                    'color' => '#333333',
                ],
            ];
            @endphp

            @foreach($testimonials as $t)
            <div class="flex-shrink-0 w-[340px] md:w-[400px] rounded-lg border border-gray-200 bg-white p-7 relative">
                <div class="absolute top-3 right-5 text-4xl font-black opacity-5 text-accent leading-none select-none">"</div>
                <div class="flex text-amber-500 mb-4 gap-0.5 text-xs">
                    @for($i=0;$i<5;$i++)<i class="fa-solid fa-star"></i>@endfor
                </div>
                <p class="text-dark/70 leading-relaxed mb-6 text-sm">
                    "{{ $t['text'] }}"
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full text-white flex items-center justify-center text-sm font-bold"
                         style="background: {{ $t['color'] }};">{{ $t['initials'] }}</div>
                    <div>
                        <p class="text-sm font-semibold text-dark">{{ $t['name'] }}</p>
                        <p class="text-xs text-dark/50">{{ $t['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Duplicate for seamless loop --}}
            @foreach($testimonials as $t)
            <div class="flex-shrink-0 w-[340px] md:w-[400px] rounded-lg border border-gray-200 bg-white p-7 relative" aria-hidden="true">
                <div class="absolute top-3 right-5 text-4xl font-black opacity-5 text-accent leading-none select-none">"</div>
                <div class="flex text-amber-500 mb-4 gap-0.5 text-xs">
                    @for($i=0;$i<5;$i++)<i class="fa-solid fa-star"></i>@endfor
                </div>
                <p class="text-dark/70 leading-relaxed mb-6 text-sm">
                    "{{ $t['text'] }}"
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full text-white flex items-center justify-center text-sm font-bold"
                         style="background: {{ $t['color'] }};">{{ $t['initials'] }}</div>
                    <div>
                        <p class="text-sm font-semibold text-dark">{{ $t['name'] }}</p>
                        <p class="text-xs text-dark/50">{{ $t['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
@keyframes scroll-x {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-scroll-x {
    animation: scroll-x 40s linear infinite;
}
.animate-scroll-x:hover {
    animation-play-state: paused;
}
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>
