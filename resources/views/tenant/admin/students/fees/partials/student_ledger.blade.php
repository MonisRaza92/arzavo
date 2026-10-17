{{-- ======================================================== --}}
{{-- 3. STUDENT DIRECTORY & FEE OVERRIDES LEDGER TABLE         --}}
{{-- ======================================================== --}}
<div class="rounded-lg bg-primary p-5 space-y-4">
    {{-- Search & Unified Filter Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2.5 grow">
            {{-- Live Search input (rounded-full, bg-secondary, NO borders) --}}
            <div class="relative w-full sm:w-72 shrink-0">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Search by student, email, roll, class, batch..."
                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-10 rounded-full focus:outline-none font-normal">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs pointer-events-none"></i>
            </div>

            {{-- Unified Filter Pills Bar (All, Paid, Partial, Defaulters, Zero Dues, Overrides, Inherited) --}}
            <div class="flex items-center gap-1 bg-secondary p-1 rounded-full text-xs overflow-x-auto scrollbar">
                <button type="button"
                        @click="setActiveFilter('all')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap"
                        :class="activeFilter === 'all' ? 'bg-primary text-brand font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    All ({{ $students->count() }})
                </button>
                <button type="button"
                        @click="setActiveFilter('paid')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5"
                        :class="activeFilter === 'paid' ? 'bg-primary text-success font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Paid ({{ $analytics['paid_count'] }})</span>
                </button>
                <button type="button"
                        @click="setActiveFilter('partial')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5"
                        :class="activeFilter === 'partial' ? 'bg-primary text-warning font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span>Partial ({{ $analytics['partial_count'] }})</span>
                </button>
                <button type="button"
                        @click="setActiveFilter('unpaid')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5"
                        :class="activeFilter === 'unpaid' ? 'bg-primary text-danger font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span>Defaulters ({{ $analytics['unpaid_count'] }})</span>
                </button>
                <button type="button"
                        @click="setActiveFilter('zero_dues')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5"
                        :class="activeFilter === 'zero_dues' ? 'bg-primary text-theme-text font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span>Zero Dues ({{ $analytics['zero_dues_count'] }})</span>
                </button>
                <button type="button"
                        @click="setActiveFilter('override')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5"
                        :class="activeFilter === 'override' ? 'bg-primary text-brand font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    <i class="fa-solid fa-asterisk text-[9px]"></i>
                    <span>Overrides ({{ $overrideCount }})</span>
                </button>
                <button type="button"
                        @click="setActiveFilter('inherited')"
                        class="px-3 py-1.5 rounded-full font-normal text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5"
                        :class="activeFilter === 'inherited' ? 'bg-primary text-brand font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    <i class="fa-solid fa-code-merge text-[9px]"></i>
                    <span>Inherited ({{ $students->count() - $overrideCount }})</span>
                </button>
            </div>
        </div>

        <div class="text-xs text-theme-text-muted font-normal text-right shrink-0">
            Showing <span class="font-semibold text-theme-text" x-text="filteredStudents.length"></span> of <span class="font-semibold text-theme-text">{{ $students->count() }}</span> students
        </div>
    </div>

    {{-- Students Ledger Table --}}
    <div class="overflow-x-auto min-h-[300px] pb-24">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider border-b-0">
                    <th class="py-3 px-3">Student / Roll</th>
                    <th class="py-3 px-3">Academic Stream</th>
                    <th class="py-3 px-3">Effective Fee Rate</th>
                    <th class="py-3 px-3">Hierarchy Resolution Source</th>
                    <th class="py-3 px-3">Paid Amount</th>
                    <th class="py-3 px-3">Balance Due</th>
                    <th class="py-3 px-3">Payment Status</th>
                    <th class="py-3 px-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y-0 text-theme-text">
                <template x-for="st in filteredStudents" :key="st.id">
                    <tr class="hover:bg-secondary/60 transition rounded-lg">
                        {{-- Student Name & Avatar --}}
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-2.5">
                                <template x-if="st.profile_picture">
                                    <img :src="'/' + st.profile_picture" :alt="st.fname" class="w-8 h-8 rounded-full object-cover shrink-0">
                                </template>
                                <template x-if="!st.profile_picture">
                                    <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center font-semibold text-brand text-xs shrink-0 uppercase"
                                         x-text="(st.fname.charAt(0) + (st.lname ? st.lname.charAt(0) : '')).toUpperCase()">
                                    </div>
                                </template>
                                <div class="min-w-0">
                                    <a :href="'/admin/student/profile/' + st.username"
                                       class="font-semibold text-theme-text hover:text-brand transition block truncate leading-tight"
                                       x-text="st.fname + ' ' + (st.lname || '')">
                                    </a>
                                    <div class="text-[10px] text-theme-text-muted truncate" x-text="st.roll_number ? ('Roll: ' + st.roll_number) : ('@' + st.username)"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Academic Placement --}}
                        <td class="py-3 px-3 text-theme-text-muted">
                            <div class="font-medium text-theme-text" x-text="st.class_name || 'No Class'"></div>
                            <div class="text-[10px] text-theme-text-muted" x-text="(st.category_name || 'No Category') + (st.batch_name ? (' • ' + st.batch_name) : '')"></div>
                        </td>

                        {{-- Effective Fee Rate --}}
                        <td class="py-3 px-3">
                            <span class="text-sm font-semibold text-theme-text" x-text="'₹' + Number(st.effective_plan.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            <span class="text-[10px] text-theme-text-muted block lowercase font-normal" x-text="'/' + (st.effective_plan.plan_type || 'month')"></span>
                        </td>

                        {{-- Hierarchy Source Badge --}}
                        <td class="py-3 px-3">
                            <template x-if="st.effective_plan.is_override">
                                <span class="px-2.5 py-1 rounded-full text-[10px] bg-warning-bg text-warning font-semibold inline-flex items-center gap-1.5"
                                      :title="st.effective_plan.override_reason || 'Custom Override'">
                                    <i class="fa-solid fa-asterisk text-[8px]"></i>
                                    <span x-text="st.effective_plan.source_label || 'Custom Override'"></span>
                                </span>
                            </template>
                            <template x-if="!st.effective_plan.is_override && st.effective_plan.has_plan">
                                <span class="px-2.5 py-1 rounded-full text-[10px] bg-secondary text-brand font-normal inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-code-merge text-[9px]"></i>
                                    <span x-text="st.effective_plan.source_label"></span>
                                </span>
                            </template>
                            <template x-if="!st.effective_plan.has_plan">
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text-muted font-normal">
                                    No Plan Configured
                                </span>
                            </template>
                        </td>

                        {{-- Paid Amount --}}
                        <td class="py-3 px-3 text-success font-medium"
                            x-text="'₹' + Number(st.paid_fee || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})">
                        </td>

                        {{-- Balance Due --}}
                        <td class="py-3 px-3 font-semibold"
                            :class="st.due_fee > 0 ? 'text-warning' : 'text-success'"
                            x-text="'₹' + Number(st.due_fee || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})">
                        </td>

                        {{-- Payment Status Badge --}}
                        <td class="py-3 px-3">
                            <template x-if="st.total_fee > 0 && st.due_fee <= 0">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-normal bg-success-bg text-success inline-flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-[8px]"></i> Paid in Full
                                </span>
                            </template>
                            <template x-if="st.paid_fee > 0 && st.due_fee > 0">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-normal bg-warning-bg text-warning inline-flex items-center gap-1">
                                    <i class="fa-solid fa-circle-half-stroke text-[8px]"></i> Partial Paid
                                </span>
                            </template>
                            <template x-if="st.paid_fee <= 0 && st.total_fee > 0">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-normal bg-danger-bg text-danger inline-flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[8px]"></i> Unpaid / Due
                                </span>
                            </template>
                            <template x-if="st.total_fee <= 0 && st.paid_fee <= 0">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-secondary text-theme-text-muted">
                                    Zero Dues
                                </span>
                            </template>
                        </td>

                        {{-- Actions Dropdown Menu --}}
                        <td class="py-3 px-3 text-right">
                            <div class="relative inline-block text-left" @click.outside="if (activeActionMenu === st.id) activeActionMenu = null">
                                <button type="button"
                                        @click.stop="toggleActionMenu(st.id, $el)"
                                        class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle transition flex items-center justify-center text-xs cursor-pointer shadow-2xs"
                                        :class="activeActionMenu === st.id ? 'bg-subtle text-brand ring-2 ring-brand/20' : ''"
                                        title="Actions">
                                    <i class="fa-solid fa-ellipsis-vertical text-xs pointer-events-none"></i>
                                </button>

                                <div x-cloak
                                     x-show="activeActionMenu === st.id"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     :class="isMenuDropUp ? 'bottom-full mb-2 origin-bottom-right' : 'top-full mt-2 origin-top-right'"
                                     class="absolute right-0 w-56 rounded-lg bg-primary shadow-2xl p-1.5 z-50 space-y-0.5 text-left border-0">

                                    {{-- 1. Collect Payment --}}
                                    <button type="button"
                                            @click="openCollectModal(st); activeActionMenu = null"
                                            class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2.5 transition cursor-pointer">
                                        <i class="fa-solid fa-wallet text-brand text-[11px] w-4 text-center"></i>
                                        <span>Collect Payment</span>
                                    </button>

                                    {{-- 2. Fee Override / Concession --}}
                                    <button type="button"
                                            @click="openOverrideModal(st); activeActionMenu = null"
                                            class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2.5 transition cursor-pointer">
                                        <i class="fa-solid fa-sliders text-brand text-[11px] w-4 text-center"></i>
                                        <span>Fee Override / Plan</span>
                                    </button>

                                    {{-- 3. Student 360 Profile --}}
                                    <a :href="'/admin/student/profile/' + st.username"
                                       class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2.5 transition">
                                        <i class="fa-solid fa-user-circle text-theme-text-muted text-[11px] w-4 text-center"></i>
                                        <span>View Student Profile</span>
                                    </a>

                                    {{-- 4. WhatsApp Fee Reminder (if due > 0 and phone exists) --}}
                                    <template x-if="st.due_fee > 0 && (st.parent_phone || st.number)">
                                        <a :href="getWhatsAppReminderUrl(st)"
                                           target="_blank"
                                           class="w-full px-3 py-2 text-xs font-normal text-success hover:bg-success-bg rounded-md flex items-center gap-2.5 transition">
                                            <i class="fa-brands fa-whatsapp text-success text-[11px] w-4 text-center"></i>
                                            <span>WhatsApp Reminder</span>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="filteredStudents.length === 0">
                    <td colspan="8" class="p-8 text-center text-theme-text-muted text-xs">
                        No students matching the current search query or filter.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
