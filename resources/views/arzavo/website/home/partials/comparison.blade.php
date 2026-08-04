{{-- Platform Comparison Section --}}
<section id="comparison" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Why Switch</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Stop managing your institute on WhatsApp & Spreadsheets.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                See how Arzavo replaces the chaos of disconnected tools with one unified command center.
            </p>
        </div>

        {{-- Comparison Table --}}
        <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="p-5 pl-6 text-xs font-bold uppercase tracking-widest text-dark/40 w-1/4">Capability</th>
                            <th class="p-5 text-center w-1/4">
                                <div class="text-xs font-bold uppercase tracking-widest text-dark/40 mb-1">Spreadsheets</div>
                                <div class="text-xs text-dark/30">Google Sheets / Excel</div>
                            </th>
                            <th class="p-5 text-center w-1/4">
                                <div class="text-xs font-bold uppercase tracking-widest text-dark/40 mb-1">WhatsApp Groups</div>
                                <div class="text-xs text-dark/30">Manual Broadcasting</div>
                            </th>
                            <th class="p-5 text-center w-1/4 bg-accent/5">
                                <div class="text-xs font-bold uppercase tracking-widest text-accent mb-1">Arzavo</div>
                                <div class="text-xs text-accent/60">All-in-One Platform</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $rows = [
                            ['feature' => 'Student Database', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'Limited rows, no relations'],
                            ['feature' => 'Automated Fee Collection', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'Manual tracking only'],
                            ['feature' => 'Attendance Tracking', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'No automation'],
                            ['feature' => 'Parent Notifications', 'sheet' => false, 'whatsapp' => 'partial', 'arzavo' => true, 'note' => 'No templates or automation'],
                            ['feature' => 'Online Course Delivery', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'Not possible'],
                            ['feature' => 'Exam & Result Engine', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'Manual grading'],
                            ['feature' => 'Role-based Staff Access', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'Shared access only'],
                            ['feature' => 'Branded Student Portal', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'Not available'],
                            ['feature' => 'Analytics Dashboard', 'sheet' => 'partial', 'whatsapp' => false, 'arzavo' => true, 'note' => 'Manual charts'],
                            ['feature' => 'Payment Gateway (UPI/Cards)', 'sheet' => false, 'whatsapp' => false, 'arzavo' => true, 'note' => 'External links only'],
                        ];
                        @endphp
                        @foreach($rows as $row)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 pl-6 text-sm font-medium text-dark">{{ $row['feature'] }}</td>
                            <td class="p-4 text-center">
                                @if($row['sheet'] === true)
                                    <i class="fa-solid fa-check text-green-500 text-xs"></i>
                                @elseif($row['sheet'] === 'partial')
                                    <i class="fa-solid fa-minus text-amber-400 text-xs"></i>
                                @else
                                    <i class="fa-solid fa-xmark text-dark/20 text-xs"></i>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($row['whatsapp'] === true)
                                    <i class="fa-solid fa-check text-green-500 text-xs"></i>
                                @elseif($row['whatsapp'] === 'partial')
                                    <i class="fa-solid fa-minus text-amber-400 text-xs"></i>
                                @else
                                    <i class="fa-solid fa-xmark text-dark/20 text-xs"></i>
                                @endif
                            </td>
                            <td class="p-4 text-center bg-accent/5">
                                <i class="fa-solid fa-check text-accent text-xs"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-gray-200 bg-accent/5">
                <p class="text-sm text-dark/60">Ready to replace your fragmented tools with one platform?</p>
                <x-button url="{{ route('register.form') }}" variant="accent" padding="px-6 py-2.5">
                    Start Free Trial <i class="fa-solid fa-arrow-right -rotate-45"></i>
                </x-button>
            </div>
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
