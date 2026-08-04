{{-- Integrations Section --}}
<section id="integrations" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #fffbf8 50%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Ecosystem</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Plugs into the tools you already use.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Arzavo integrates with leading payment, communication, and video conferencing platforms so your workflow stays seamless.
            </p>
        </div>

        {{-- Integration Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
            $integrations = [
                ['name' => 'Razorpay', 'icon' => 'fa-credit-card', 'color' => '#2B84EA', 'desc' => 'Payment Gateway'],
                ['name' => 'WhatsApp', 'icon' => 'fa-brands fa-whatsapp', 'color' => '#25D366', 'desc' => 'Messaging'],
                ['name' => 'Zoom', 'icon' => 'fa-video', 'color' => '#2D8CFF', 'desc' => 'Live Classes'],
                ['name' => 'Google Meet', 'icon' => 'fa-video', 'color' => '#00897B', 'desc' => 'Video Calls'],
                ['name' => 'SMS Gateway', 'icon' => 'fa-comment-sms', 'color' => '#FF6F00', 'desc' => 'Bulk SMS'],
                ['name' => 'AWS S3', 'icon' => 'fa-cloud', 'color' => '#FF9900', 'desc' => 'File Storage'],
            ];
            @endphp
            @foreach($integrations as $integration)
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center hover:-translate-y-1 transition-transform duration-300 group">
                <div class="w-12 h-12 mx-auto rounded-lg flex items-center justify-center mb-4 transition-colors duration-300"
                     style="background: {{ $integration['color'] }}15; color: {{ $integration['color'] }};">
                    <i class="{{ str_contains($integration['icon'], 'fa-brands') ? $integration['icon'] : 'fa-solid '.$integration['icon'] }} text-xl"></i>
                </div>
                <h4 class="text-sm font-semibold text-dark mb-1">{{ $integration['name'] }}</h4>
                <p class="text-xs text-dark/50">{{ $integration['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- More integrations note --}}
        <div class="mt-8 text-center">
            <p class="text-sm text-dark/50">
                <i class="fa-solid fa-plug text-accent/40 mr-1"></i>
                And many more integrations coming soon — Email, Google Calendar, Cashfree, PhonePe, and custom webhooks.
            </p>
        </div>
    </div>
</section>

<style>
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>
