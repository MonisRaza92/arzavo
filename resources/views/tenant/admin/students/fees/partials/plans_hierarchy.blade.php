{{-- ======================================================== --}}
{{-- 2. HIERARCHY MASTER FEE PLANS (CATEGORY / CLASS / BATCH)  --}}
{{-- ======================================================== --}}
<div class="space-y-5">
    {{-- Hierarchy Cascade Explanation Ribbon --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 text-xs text-theme-text-muted">
            <span class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0 font-semibold">
                <i class="fa-solid fa-layer-group"></i>
            </span>
            <span class="leading-relaxed">
                <strong>Cascade Inheritance Rule:</strong> Fees resolve in order:
                <span class="text-theme-text font-medium">Category</span> &rarr;
                <span class="text-theme-text font-medium">Class</span> (Overrides Category) &rarr;
                <span class="text-theme-text font-medium">Subject</span> (Overrides Class) &rarr;
                <span class="text-theme-text font-medium">Batch</span> (Overrides Subject) &rarr;
                <span class="text-brand font-semibold">Student Override</span> (Final).
            </span>
        </div>
        <button type="button"
                @click="openCreatePlanModal()"
                class="px-4 py-2 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-1.5 shrink-0 cursor-pointer shadow-xs">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Add Fee Plan</span>
        </button>
    </div>

    {{-- Master Plans Table Container --}}
    <div class="rounded-lg bg-primary p-5 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Configured Master Fee Plans</h3>
                <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                    Centrally managed rates applied dynamically to students matching each academic tier.
                </p>
            </div>
            <div class="text-xs text-theme-text-muted font-normal">
                Total: <span class="font-semibold text-theme-text">{{ $feeStructures->count() }}</span> Plans configured
            </div>
        </div>

        @if($feeStructures->isEmpty())
            <div class="p-8 text-center rounded-lg bg-secondary space-y-3">
                <div class="w-12 h-12 rounded-full bg-brand/10 text-brand mx-auto flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-theme-text">No Hierarchy Fee Plans Created Yet</h4>
                    <p class="text-xs text-theme-text-muted mt-1 max-w-md mx-auto font-normal">
                        Create a Category-level fee plan (e.g. ₹1,000) or Class-level fee plan (e.g. Class 10: ₹1,200). All matching students will inherit this rate automatically!
                    </p>
                </div>
                <button type="button"
                        @click="openCreatePlanModal()"
                        class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Your First Fee Plan</span>
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider border-b-0">
                            <th class="py-3 px-3">Hierarchy Tier</th>
                            <th class="py-3 px-3">Target Scope</th>
                            <th class="py-3 px-3">Cadence</th>
                            <th class="py-3 px-3">Base Fee Rate</th>
                            <th class="py-3 px-3">Due Day</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0 text-theme-text">
                        @foreach($feeStructures as $plan)
                            <tr class="hover:bg-secondary/60 transition rounded-lg">
                                {{-- Hierarchy Tier Badge --}}
                                <td class="py-3.5 px-3">
                                    @php
                                        $tierStyles = [
                                            'category' => 'bg-secondary text-brand',
                                            'class' => 'bg-brand/10 text-brand font-semibold',
                                            'subject' => 'bg-secondary text-theme-text',
                                            'batch' => 'bg-success-bg text-success',
                                        ];
                                        $tierIcons = [
                                            'category' => 'fa-layer-group',
                                            'class' => 'fa-graduation-cap',
                                            'subject' => 'fa-book',
                                            'batch' => 'fa-users',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-normal inline-flex items-center gap-1.5 {{ $tierStyles[$plan->scope] ?? 'bg-secondary text-theme-text' }}">
                                        <i class="fa-solid {{ $tierIcons[$plan->scope] ?? 'fa-circle' }} text-[9px]"></i>
                                        <span>{{ ucfirst($plan->scope) }} Tier</span>
                                    </span>
                                </td>

                                {{-- Target Scope Name --}}
                                <td class="py-3.5 px-3">
                                    <div class="font-semibold text-xs text-theme-text">
                                        {{ $plan->target_name }}
                                    </div>
                                    @if($plan->title)
                                        <div class="text-[10px] text-theme-text-muted font-normal">{{ $plan->title }}</div>
                                    @endif
                                </td>

                                {{-- Cadence --}}
                                <td class="py-3.5 px-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text-muted font-normal">
                                        {{ ucfirst($plan->plan_type) }}
                                    </span>
                                </td>

                                {{-- Base Fee Rate --}}
                                <td class="py-3.5 px-3">
                                    <span class="text-sm font-semibold text-theme-text">
                                        ₹{{ number_format($plan->amount, 2) }}
                                    </span>
                                </td>

                                {{-- Due Day --}}
                                <td class="py-3.5 px-3 text-theme-text-muted font-normal">
                                    Every {{ $plan->due_day }}{{ in_array($plan->due_day, [1, 21, 31]) ? 'st' : (in_array($plan->due_day, [2, 22]) ? 'nd' : (in_array($plan->due_day, [3, 23]) ? 'rd' : 'th')) }}
                                </td>

                                {{-- Status --}}
                                <td class="py-3.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-normal {{ $plan->is_active ? 'bg-success-bg text-success' : 'bg-secondary text-theme-text-muted' }}">
                                        {{ $plan->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="py-3.5 px-3 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button"
                                                @click="openEditPlanModal(@js($plan))"
                                                class="px-2.5 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1 cursor-pointer"
                                                title="Edit Plan">
                                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                            <span>Edit</span>
                                        </button>
                                        <form action="{{ route('admin.students.fee-plans.delete', $plan->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this fee plan? Matching students will automatically inherit from their parent tier.');"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 bg-secondary text-danger hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1 cursor-pointer"
                                                    title="Delete Plan">
                                                <i class="fa-solid fa-trash text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
