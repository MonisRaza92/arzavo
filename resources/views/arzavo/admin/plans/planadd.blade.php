<div id="planModal" class="fixed inset-0 flex bg-black/80 hidden items-center justify-center z-100 p-4 backdrop-blur-xs">

    <div class="modal-content bg-primary border-primary border-rounded w-full max-w-4xl shadow-2xl p-6 space-y-6 overflow-y-auto max-h-[90vh]">

        <!-- HEADER -->
        <div class="flex justify-between items-start pb-4 border-bottom">
            <div>
                <h2 id="planModalTitle" class="text-xl font-bold text-primary">Create Plan</h2>
                <p class="text-xs text-tertiary mt-0.5">Define pricing, features, visibility, and limits for this tier.</p>
            </div>

            <button type="button" onclick="closeModel('planModal')" class="text-tertiary hover:text-primary text-xl cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="planForm" action="{{ route('arzavo.admin.plans.store') }}" method="POST">
            @csrf
            <div id="planFormMethod"></div>

            <!-- BASIC INFO -->
            <div class="bg-secondary p-5 border-rounded space-y-4">
                <h3 class="font-semibold text-sm text-primary flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-accent"></i> Basic Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Plan Name</label>
                        <input id="plan_name" name="name" placeholder="e.g. Starter / Pro / Enterprise"
                            class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Slug</label>
                        <input id="plan_slug" name="slug" placeholder="e.g. starter-plan"
                            class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Monthly Price (₹)</label>
                        <input id="plan_monthly_price" name="monthly_price" type="number" step="0.01" placeholder="0 for Free"
                            class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Yearly Price (₹)</label>
                        <input id="plan_yearly_price" name="yearly_price" type="number" step="0.01" placeholder="Yearly Price"
                            class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Trial Days</label>
                        <input id="plan_trial_days" name="trial_days" type="number" placeholder="e.g. 7"
                            class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-secondary block mb-1">Short Description (Tagline)</label>
                    <input id="plan_short_description" name="short_description" placeholder="Best for small institutions and tutors"
                        class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary">
                </div>

                <div>
                    <label class="text-xs font-semibold text-secondary block mb-1">Detailed Description</label>
                    <textarea id="plan_description" name="description" placeholder="Full details of features included in this plan..." rows="3"
                        class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary"></textarea>
                </div>
            </div>

            <!-- SETTINGS & VISIBILITY TOGGLES -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-5">

                <!-- ACTIVE -->
                <div class="flex items-center justify-between border-primary border-rounded px-4 py-3 bg-primary">
                    <div class="pr-2">
                        <p class="font-semibold text-xs text-primary"><i class="fa-regular fa-circle-dot mr-1.5 text-green-500"></i> Active Status</p>
                        <p class="text-[11px] text-tertiary">Enable or disable this plan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="plan_is_active" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-accent"></div>
                    </label>
                </div>

                <!-- POPULAR -->
                <div class="flex items-center justify-between border-primary border-rounded px-4 py-3 bg-primary">
                    <div class="pr-2">
                        <p class="font-semibold text-xs text-primary"><i class="fa-regular fa-star mr-1.5 text-amber-500"></i> Popular Badge</p>
                        <p class="text-[11px] text-tertiary">Highlight as recommended plan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="plan_is_popular" name="is_popular" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-accent"></div>
                    </label>
                </div>

                <!-- COMING SOON -->
                <div class="flex items-center justify-between border-primary border-rounded px-4 py-3 bg-primary">
                    <div class="pr-2">
                        <p class="font-semibold text-xs text-primary"><i class="fa-solid fa-clock mr-1.5 text-blue-500"></i> Coming Soon</p>
                        <p class="text-[11px] text-tertiary">Shows coming soon badge & disables button</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="plan_is_coming_soon" name="is_coming_soon" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-accent"></div>
                    </label>
                </div>

                <!-- HIDDEN / ADMIN ONLY -->
                <div class="flex items-center justify-between border-primary border-rounded px-4 py-3 bg-primary">
                    <div class="pr-2">
                        <p class="font-semibold text-xs text-primary"><i class="fa-solid fa-eye-slash mr-1.5 text-purple-500"></i> Hide from Website</p>
                        <p class="text-[11px] text-tertiary">Admin-only plan (manual tenant assignment)</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="plan_is_hidden" name="is_hidden" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-accent"></div>
                    </label>
                </div>

            </div>

            <!-- FEATURES LIST -->
            <div class="bg-secondary p-5 border-rounded">
                <h3 class="font-semibold text-sm text-primary mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-accent"></i> Features Included
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach(config('plan.features') as $key => $label)
                        <div class="flex justify-between items-center bg-primary p-2.5 border-rounded border-primary">
                            <span class="text-xs font-medium text-primary">{{ $label }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="features[{{ $key }}]" value="1" class="feature-checkbox sr-only peer" data-feature-key="{{ $key }}">
                                <div class="w-8 h-4.5 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-accent"></div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- LIMITS -->
            <div class="bg-secondary p-5 border-rounded mt-4">
                <h3 class="font-semibold text-sm text-primary mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-accent"></i> Resource Limits
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach(config('plan.limits') as $key => $label)
                        <div class="flex justify-between items-center bg-primary p-2.5 border-rounded border-primary gap-3">
                            <span class="text-xs font-medium text-primary">{{ $label }}</span>
                            <input type="number" name="limits[{{ $key }}]" id="limit_{{ $key }}" placeholder="Unlimited"
                                class="limit-input input border-primary border-rounded p-1.5 px-2.5 text-xs bg-secondary text-right w-28 text-primary font-mono">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="flex justify-end gap-3 pt-4 border-top">
                <button type="button" onclick="closeModel('planModal')" class="px-4 py-2 text-xs font-semibold border-rounded border-primary bg-primary text-secondary hover:bg-hover-secondary transition">
                    Cancel
                </button>

                <button type="submit" class="px-5 py-2 text-xs font-semibold bg-invert text-invert border-rounded shadow-sm hover:opacity-90 transition flex items-center gap-2">
                    <i class="fa-regular fa-floppy-disk"></i>
                    <span id="planSubmitBtnText">Save Plan</span>
                </button>
            </div>

        </form>
    </div>
</div>

{{-- EDIT / CREATE PLAN JAVASCRIPT --}}
<script>
function openCreatePlanModal() {
    const form = document.getElementById('planForm');
    form.action = "{{ route('arzavo.admin.plans.store') }}";
    document.getElementById('planFormMethod').innerHTML = '';
    document.getElementById('planModalTitle').innerText = 'Create Plan';
    document.getElementById('planSubmitBtnText').innerText = 'Save Plan';

    form.reset();
    document.getElementById('plan_is_active').checked = true;
    document.getElementById('plan_is_popular').checked = false;
    document.getElementById('plan_is_coming_soon').checked = false;
    document.getElementById('plan_is_hidden').checked = false;

    // Reset feature checkboxes
    document.querySelectorAll('.feature-checkbox').forEach(cb => cb.checked = false);
    // Reset limits
    document.querySelectorAll('.limit-input').forEach(inp => inp.value = '');

    openModel('planModal');
}

function editPlan(planId) {
    const form = document.getElementById('planForm');
    const baseUrl = "{{ url('plans') }}";
    form.action = `${baseUrl}/${planId}`;
    document.getElementById('planFormMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('planModalTitle').innerText = 'Edit Plan';
    document.getElementById('planSubmitBtnText').innerText = 'Update Plan';

    // Fetch Plan Details
    fetch(`${baseUrl}/${planId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(response => {
        if (!response.data) return;
        const plan = response.data;

        document.getElementById('plan_name').value = plan.name || '';
        document.getElementById('plan_slug').value = plan.slug || '';
        document.getElementById('plan_monthly_price').value = plan.monthly_price ?? '';
        document.getElementById('plan_yearly_price').value = plan.yearly_price ?? '';
        document.getElementById('plan_trial_days').value = plan.trial_days ?? '';
        document.getElementById('plan_short_description').value = plan.short_description || '';
        document.getElementById('plan_description').value = plan.description || '';

        document.getElementById('plan_is_active').checked = Boolean(plan.is_active);
        document.getElementById('plan_is_popular').checked = Boolean(plan.is_popular);
        document.getElementById('plan_is_coming_soon').checked = Boolean(plan.is_coming_soon);
        document.getElementById('plan_is_hidden').checked = Boolean(plan.is_hidden);

        // Features
        document.querySelectorAll('.feature-checkbox').forEach(cb => {
            const key = cb.getAttribute('data-feature-key');
            cb.checked = Boolean(plan.features && plan.features[key]);
        });

        // Limits
        document.querySelectorAll('.limit-input').forEach(inp => {
            const key = inp.name.replace('limits[', '').replace(']', '');
            inp.value = (plan.limits && plan.limits[key] !== undefined && plan.limits[key] !== null) ? plan.limits[key] : '';
        });

        openModel('planModal');
    })
    .catch(err => {
        console.error('Error fetching plan:', err);
        alert('Failed to load plan details.');
    });
}
</script>