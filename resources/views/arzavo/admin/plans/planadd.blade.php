<!-- PLAN CREATE / EDIT MODAL (Rule 4.2 Exception: shadow-2xl for large dialogs) -->
<div id="planModal"
     onclick="if(event.target === this) closeModel('planModal')"
     class="fixed inset-0 bg-black/70 flex items-center justify-center z-[999] p-4 overflow-y-auto"
     style="display: none;">

    <div class="rounded-lg bg-primary shadow-2xl w-full max-w-4xl p-6 sm:p-8 space-y-6 my-auto max-h-[90vh] overflow-y-auto relative">

        <!-- MODAL HEADER -->
        <div class="flex items-start justify-between pb-2">
            <div>
                <h3 id="planModalTitle" class="text-lg font-semibold text-theme-text">Create Subscription Tier</h3>
                <p class="text-xs text-theme-text-muted mt-0.5">Specify package pricing, module features, student limits, and promotional visibility.</p>
            </div>

            <button type="button" onclick="closeModel('planModal')"
                    class="text-theme-text-muted hover:text-theme-text text-sm p-1.5 rounded-full hover:bg-secondary transition cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form id="planForm" action="{{ route('arzavo.admin.plans.store') }}" method="POST" class="space-y-6">
            @csrf
            <div id="planFormMethod"></div>

            <!-- 1. BASIC INFORMATION BLOCK -->
            <div class="p-5 rounded-lg bg-secondary space-y-4">
                <h4 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-brand text-xs"></i>
                    <span>Basic Details & Pricing</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Plan Name</label>
                        <input id="plan_name" name="name" required placeholder="e.g. Starter Academy / Enterprise Fleet"
                               oninput="autoGenerateSlug(this.value)"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Slug (Unique identifier)</label>
                        <input id="plan_slug" name="slug" required placeholder="e.g. starter-academy"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Monthly Price (₹)</label>
                        <input id="plan_monthly_price" name="monthly_price" type="number" step="0.01" required placeholder="0 for Free tier"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Yearly Price (₹ Optional)</label>
                        <input id="plan_yearly_price" name="yearly_price" type="number" step="0.01" placeholder="e.g. 9999"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Free Trial Days</label>
                        <input id="plan_trial_days" name="trial_days" type="number" placeholder="e.g. 7 or 14"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Short Tagline</label>
                    <input id="plan_short_description" name="short_description" placeholder="Ideal for growing independent tuition centers and coaching institutes"
                           class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Full Description</label>
                    <textarea id="plan_description" name="description" rows="2" placeholder="Comprehensive breakdown of package offerings..."
                              class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs p-4 rounded-lg focus:outline-none"></textarea>
                </div>
            </div>

            <!-- 2. VISIBILITY & BADGES SWITCHES -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Active Toggle -->
                <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-theme-text">Active Status</p>
                        <p class="text-[11px] text-theme-text-muted">Available for subscription</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="plan_is_active" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-success"></div>
                    </label>
                </div>

                <!-- Popular Badge -->
                <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-theme-text">Popular Flag</p>
                        <p class="text-[11px] text-theme-text-muted">Highlight with star badge</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="plan_is_popular" name="is_popular" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-warning"></div>
                    </label>
                </div>

                <!-- Coming Soon -->
                <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-theme-text">Coming Soon</p>
                        <p class="text-[11px] text-theme-text-muted">Mark as in development</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="plan_is_coming_soon" name="is_coming_soon" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-info"></div>
                    </label>
                </div>

                <!-- Admin Only / Hidden -->
                <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-theme-text">Admin Restricted</p>
                        <p class="text-[11px] text-theme-text-muted">Hidden from public pricing</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="plan_is_hidden" name="is_hidden" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand"></div>
                    </label>
                </div>

                <!-- Limited Time Offer -->
                <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3 sm:col-span-2">
                    <div class="pr-2">
                        <p class="text-xs font-semibold text-theme-text">Limited Time Promo</p>
                        <p class="text-[11px] text-theme-text-muted">Highlight with urgency indicator</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="plan_is_limited_time" name="is_limited_time" value="1"
                               onchange="document.getElementById('plan_limited_time_container').classList.toggle('hidden', !this.checked)"
                               class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-danger"></div>
                    </label>
                </div>
            </div>

            <div id="plan_limited_time_container" class="hidden p-4 rounded-lg bg-secondary">
                <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Promotion End Date</label>
                <input type="date" id="plan_limited_time_ends_at" name="limited_time_ends_at"
                       class="w-full sm:w-72 bg-primary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
            </div>

            <!-- 3. RESOURCE QUOTAS / LIMITS -->
            <div class="p-5 rounded-lg bg-secondary space-y-4">
                <h4 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-server text-brand text-xs"></i>
                    <span>Resource Quota Limits (Leave empty for Unlimited)</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach(config('plan.limits') as $key => $label)
                        <div>
                            <label class="text-xs font-normal text-theme-text-muted block mb-1.5 truncate">{{ $label }}</label>
                            <input id="limit_{{ $key }}" name="limits[{{ $key }}]" placeholder="Unlimited"
                                   class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. MODULE CAPABILITIES / FEATURES MATRIX -->
            <div class="p-5 rounded-lg bg-secondary space-y-4">
                <h4 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center justify-between gap-2">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-puzzle-piece text-brand text-xs"></i>
                        <span>Included Module Features</span>
                    </span>
                    <button type="button" onclick="toggleAllPlanFeatures(true)" class="text-[11px] text-brand hover:underline font-normal cursor-pointer">
                        Select All
                    </button>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(config('plan.features') as $key => $label)
                        <label class="p-3 rounded-lg bg-primary flex items-center justify-between gap-2 cursor-pointer hover:bg-subtle transition">
                            <span class="text-xs text-theme-text truncate select-none">{{ $label }}</span>
                            <input type="checkbox" id="feature_{{ $key }}" name="features[{{ $key }}]" value="1"
                                   class="rounded text-brand focus:ring-0 cursor-pointer w-4 h-4">
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- MODAL ACTIONS -->
            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeModel('planModal')"
                        class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="planSubmitBtn"
                        class="px-6 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 cursor-pointer">
                    Save Plan Tier
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function autoGenerateSlug(name) {
        const slugInput = document.getElementById('plan_slug');
        if (slugInput && !slugInput.dataset.manual) {
            slugInput.value = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }
    }

    document.getElementById('plan_slug')?.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    function toggleAllPlanFeatures(check) {
        document.querySelectorAll('[id^="feature_"]').forEach(cb => cb.checked = check);
    }

    function openCreatePlanModal() {
        const form = document.getElementById('planForm');
        form.action = "{{ route('arzavo.admin.plans.store') }}";
        document.getElementById('planFormMethod').innerHTML = '';
        document.getElementById('planModalTitle').innerText = 'Create Subscription Tier';
        document.getElementById('planSubmitBtn').innerText = 'Create Plan';

        form.reset();
        document.getElementById('plan_slug').dataset.manual = '';
        document.getElementById('plan_is_active').checked = true;
        document.getElementById('plan_is_popular').checked = false;
        document.getElementById('plan_is_coming_soon').checked = false;
        document.getElementById('plan_is_hidden').checked = false;
        document.getElementById('plan_is_limited_time').checked = false;
        document.getElementById('plan_limited_time_container').classList.add('hidden');

        document.querySelectorAll('[id^="feature_"]').forEach(cb => cb.checked = false);
        document.querySelectorAll('[id^="limit_"]').forEach(input => input.value = '');

        openModel('planModal');
    }

    function editPlan(id) {
        fetch(`/plans/${id}/edit`)
            .then(res => res.json())
            .then(res => {
                if (!res.success) return alert('Could not load plan details');
                const plan = res.data;

                const form = document.getElementById('planForm');
                form.action = `/plans/${plan.id}`;
                document.getElementById('planFormMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('planModalTitle').innerText = 'Edit Plan: ' + plan.name;
                document.getElementById('planSubmitBtn').innerText = 'Update Plan';

                document.getElementById('plan_name').value = plan.name || '';
                document.getElementById('plan_slug').value = plan.slug || '';
                document.getElementById('plan_slug').dataset.manual = 'true';
                document.getElementById('plan_monthly_price').value = plan.monthly_price !== null ? plan.monthly_price : '';
                document.getElementById('plan_yearly_price').value = plan.yearly_price !== null ? plan.yearly_price : '';
                document.getElementById('plan_trial_days').value = plan.trial_days !== null ? plan.trial_days : '';
                document.getElementById('plan_short_description').value = plan.short_description || '';
                document.getElementById('plan_description').value = plan.description || '';

                document.getElementById('plan_is_active').checked = Boolean(plan.is_active);
                document.getElementById('plan_is_popular').checked = Boolean(plan.is_popular);
                document.getElementById('plan_is_coming_soon').checked = Boolean(plan.is_coming_soon);
                document.getElementById('plan_is_hidden').checked = Boolean(plan.is_hidden);
                document.getElementById('plan_is_limited_time').checked = Boolean(plan.is_limited_time);

                const limitEndInput = document.getElementById('plan_limited_time_ends_at');
                const limitEndCont = document.getElementById('plan_limited_time_container');
                if (plan.limited_time_ends_at) {
                    limitEndInput.value = plan.limited_time_ends_at.substring(0, 10);
                    limitEndCont.classList.remove('hidden');
                } else {
                    limitEndInput.value = '';
                    limitEndCont.classList.toggle('hidden', !plan.is_limited_time);
                }

                // Features
                const features = plan.features || {};
                document.querySelectorAll('[id^="feature_"]').forEach(cb => {
                    const key = cb.id.replace('feature_', '');
                    cb.checked = Boolean(features[key]);
                });

                // Limits
                const limits = plan.limits || {};
                document.querySelectorAll('[id^="limit_"]').forEach(input => {
                    const key = input.id.replace('limit_', '');
                    input.value = (limits[key] !== undefined && limits[key] !== null) ? limits[key] : '';
                });

                openModel('planModal');
            })
            .catch(err => {
                console.error(err);
                alert('Network error loading plan details.');
            });
    }

    function openModel(id) {
        const el = document.getElementById(id);
        if (el) {
            el.style.display = 'flex';
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeModel(id) {
        const el = document.getElementById(id);
        if (el) {
            el.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModel('planModal');
        }
    });
</script>