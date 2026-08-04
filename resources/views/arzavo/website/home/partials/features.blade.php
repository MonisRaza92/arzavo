{{-- Features Section --}}
<section id="features" class="relative py-20 overflow-hidden"
    style="background: linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);">

    {{-- Background --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-10 left-10 w-[450px] h-[450px] rounded-full opacity-10"
            style="background: radial-gradient(circle, rgba(146,0,0,0.08) 0%, transparent 70%);"></div>
    </div>

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Core Capabilities</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Everything you need to scale your education business.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Ditch fragmented tools like WhatsApp, Google Drive, and spreadsheets. Arzavo brings your entire digital academy, student operations, and finances into one unified platform.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Card 1 - LMS --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-graduation-cap text-lg"></i>
                </div>
                <h3 class="text-xl font-semibold text-dark mb-3">Branded Learning Management (LMS)</h3>
                <p class="text-dark/60 leading-relaxed text-sm">
                    Deliver a premium student experience with your own branded storefront. Upload video lectures (with piracy protection), PDFs, assignments, and mock test series directly to students.
                </p>
            </div>

            {{-- Card 2 - Billing --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                </div>
                <h3 class="text-xl font-semibold text-dark mb-3">Automated Fee & Invoicing</h3>
                <p class="text-dark/60 leading-relaxed text-sm">
                    Automate billing and collections. Set up recurring payments, generate professional GST invoices, and automatically send automated fee reminders over WhatsApp and SMS.
                </p>
            </div>

            {{-- Card 3 - Attendance --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-clipboard-user text-lg"></i>
                </div>
                <h3 class="text-xl font-semibold text-dark mb-3">Smart Hybrid Attendance</h3>
                <p class="text-dark/60 leading-relaxed text-sm">
                    Track offline attendance with QR codes, barcode scanners, or standard web dashboards. Instantly trigger real-time check-in and check-out alerts to parents.
                </p>
            </div>

            {{-- Card 4 - Staff HR --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <h3 class="text-xl font-semibold text-dark mb-3">Granular Roles & Staff HR</h3>
                <p class="text-dark/60 leading-relaxed text-sm">
                    Add sub-admins, teachers, counselors, and accountants. Define precise custom permissions, track teaching logs, and manage salaries from a single admin panel.
                </p>
            </div>

            {{-- Card 5 - Reports --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
                <h3 class="text-xl font-semibold text-dark mb-3">Institute Analytics Engine</h3>
                <p class="text-dark/60 leading-relaxed text-sm">
                    Monitor institute health in real-time. Analyze active student growth, month-on-month fee collections, batch performance averages, and teacher efficiency reports.
                </p>
            </div>

            {{-- Card 6 - Communication --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-comment-sms text-lg"></i>
                </div>
                <h3 class="text-xl font-semibold text-dark mb-3">WhatsApp & SMS Broadcasts</h3>
                <p class="text-dark/60 leading-relaxed text-sm">
                    Send urgent announcements, test schedules, or student updates. Custom templates allow personalizing variables like student name, score, and branch name.
                </p>
            </div>

        </div>
    </div>
</section>

<style>
.{
    opacity: 0; transform: translateY(15px);
    transition: opacity 0.5s ease, transform 0.5s ease;
    transition-delay: var(--reveal-delay, 0s);
}
..visible { opacity: 1; transform: translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const obs = new IntersectionObserver(entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }), { threshold: 0.05 });
    document.querySelectorAll('.').forEach(el => obs.observe(el));
});
</script>
