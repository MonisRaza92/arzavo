@extends('layouts.arzavo.tenant-modal')
@section('title', 'Create Institute - ' . config('app.name'))

@section('content')

<div x-data="tenantWizard()" class="w-full relative">

    <!-- Standalone Popup Modal Dialog (bg-primary, rounded-lg, shadow-2xl) -->
    <div class="bg-primary rounded-lg shadow-2xl p-5 sm:p-6 relative">

        <!-- Modal Top Header Bar with Exit Button -->
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-secondary">
            <div class="flex items-center gap-2.5 min-w-0 pr-3">
                <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-primary text-xs shrink-0">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-semibold text-theme-text leading-tight truncate">Create Educational Institute</h3>
                    <p class="text-[10px] text-theme-text-muted font-normal truncate">Setup your new multi-tenant campus workspace</p>
                </div>
            </div>

            <a href="{{ route('tenants.index') }}"
               class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition shrink-0"
               title="Exit & Return to Institutes">
                <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <!-- Progress Header -->
        <div class="mb-4">
            <div class="flex justify-between items-center text-xs text-theme-text-muted mb-2 font-normal">
                <span>Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
                <span x-text="Math.round((step / totalSteps) * 100) + '% complete'"></span>
            </div>
            <div class="w-full bg-secondary h-1.5 rounded-full overflow-hidden">
                <div class="bg-brand h-1.5 rounded-full transition-all duration-300"
                     :style="'width:' + (step / totalSteps * 100) + '%'"></div>
            </div>
            <!-- Step dots -->
            <div class="flex justify-center gap-1.5 mt-2">
                <template x-for="i in totalSteps" :key="i">
                    <div :class="i <= step ? 'bg-brand scale-110' : 'bg-secondary'"
                         class="w-1.5 h-1.5 rounded-full transition-all duration-200"></div>
                </template>
            </div>
        </div>

        <!-- STEP 1: Name & Subdomain -->
        <div x-show="step === 1" x-transition>
            <div class="mb-5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 1</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">Let's build your educational institute</h2>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">Start with your coaching, school, or college name and a unique web URL.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-theme-text mb-1.5 font-normal">Institute / Academy / College Name</label>
                    <input x-model="form.name" type="text" placeholder="e.g. Al-Shifa Academy, Apex Coaching"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted rounded-full px-4 py-3 text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs text-theme-text mb-1.5 font-normal">Institute Web URL (Subdomain)</label>
                    <div class="relative">
                        <input x-model="form.subdomain" @input="
                            form.subdomain = form.subdomain.toLowerCase().replace(/[^a-z0-9-]/g, '');
                            if(form.subdomain.length >= 3){
                                checking = true;
                                fetch(`/check-subdomain?subdomain=${form.subdomain}`)
                                    .then(res => res.json())
                                    .then(data => { available = data.available; checking = false; });
                            } else { available = null; }
                        " type="text" placeholder="yourinstitute"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted rounded-full px-4 py-3 pr-36 text-xs focus:outline-none">
                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-theme-text-muted font-normal pointer-events-none">.{{ config('app.domain') }}</span>
                    </div>
                    <p class="text-[11px] mt-1.5 font-normal">
                        <span x-show="checking" class="text-theme-text-muted"><i class="fa-solid fa-spinner animate-spin mr-1"></i>Checking...</span>
                        <span x-show="available === true" class="text-success"><i class="fa-solid fa-circle-check mr-1"></i>Available</span>
                        <span x-show="available === false" class="text-danger"><i class="fa-solid fa-circle-xmark mr-1"></i>Already taken</span>
                    </p>
                    <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Lowercase letters, numbers and hyphens only.</p>
                </div>
            </div>

            <button @click="nextStep()" :disabled="!form.name || !form.subdomain || available === false"
                    class="mt-6 w-full bg-brand text-primary font-normal py-3 px-6 rounded-full text-xs transition disabled:opacity-40 disabled:cursor-not-allowed hover:opacity-90 inline-flex items-center justify-center gap-2">
                Continue <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </button>
        </div>

        <!-- STEP 2: Organization Type -->
        <div x-show="step === 2" x-transition>
            <div class="mb-5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 2</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">Tell us about your organization</h2>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">This helps us personalize your dashboard tools.</p>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <template x-for="item in orgTypes">
                    <button @click="toggle('org', item)"
                            :class="isSelected('org', item) ? 'bg-brand text-primary font-semibold' : 'bg-secondary text-theme-text hover:bg-subtle font-normal'"
                            class="rounded-full p-2.5 text-xs text-center transition">
                        <span x-text="item"></span>
                    </button>
                </template>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button @click="prevStep()" class="text-theme-text-muted hover:text-theme-text text-xs flex items-center gap-1 font-normal">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                </button>
                <div class="flex items-center gap-2">
                    <button @click="nextStep()" class="text-theme-text-muted hover:text-theme-text text-xs px-3 py-2 font-normal">Skip</button>
                    <button @click="nextStep()" class="bg-brand text-primary text-xs font-normal px-5 py-3 rounded-full transition hover:opacity-90 inline-flex items-center gap-1.5">
                        Next <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 3: Offerings -->
        <div x-show="step === 3" x-transition>
            <div class="mb-5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 3</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">What will you offer?</h2>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">Choose everything you plan to provide.</p>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <template x-for="item in offerings">
                    <button @click="toggle('offer', item)"
                            :class="isSelected('offer', item) ? 'bg-brand text-primary font-semibold' : 'bg-secondary text-theme-text hover:bg-subtle font-normal'"
                            class="rounded-full p-2.5 text-xs text-center transition">
                        <span x-text="item"></span>
                    </button>
                </template>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button @click="prevStep()" class="text-theme-text-muted hover:text-theme-text text-xs flex items-center gap-1 font-normal">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                </button>
                <div class="flex items-center gap-2">
                    <button @click="nextStep()" class="text-theme-text-muted hover:text-theme-text text-xs px-3 py-2 font-normal">Skip</button>
                    <button @click="nextStep()" class="bg-brand text-primary text-xs font-normal px-5 py-3 rounded-full transition hover:opacity-90 inline-flex items-center gap-1.5">
                        Next <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 4: Stage -->
        <div x-show="step === 4" x-transition>
            <div class="mb-5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 4</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">Where are you starting from?</h2>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">We'll tailor your setup based on this.</p>
            </div>

            <div class="space-y-2">
                <template x-for="item in stages">
                    <button @click="selectSingle('stage', item)"
                            :class="form.stage === item ? 'bg-brand text-primary font-semibold' : 'bg-secondary text-theme-text hover:bg-subtle font-normal'"
                            class="w-full rounded-full p-2.5 px-4 text-xs text-left flex justify-between items-center transition">
                        <span x-text="item"></span>
                        <i x-show="form.stage === item" class="fa-solid fa-circle-check text-xs"></i>
                    </button>
                </template>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button @click="prevStep()" class="text-theme-text-muted hover:text-theme-text text-xs flex items-center gap-1 font-normal">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                </button>
                <div class="flex items-center gap-2">
                    <button @click="nextStep()" class="text-theme-text-muted hover:text-theme-text text-xs px-3 py-2 font-normal">Skip</button>
                    <button @click="nextStep()" class="bg-brand text-primary text-xs font-normal px-5 py-3 rounded-full transition hover:opacity-90 inline-flex items-center gap-1.5">
                        Next <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 5: Goals -->
        <div x-show="step === 5" x-transition>
            <div class="mb-5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 5</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">What's your primary goal?</h2>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">This helps us optimize your workflow.</p>
            </div>

            <div class="space-y-2">
                <template x-for="item in goals">
                    <button @click="toggle('goal', item)"
                            :class="isSelected('goal', item) ? 'bg-brand text-primary font-semibold' : 'bg-secondary text-theme-text hover:bg-subtle font-normal'"
                            class="w-full rounded-full p-2.5 px-4 text-xs text-left flex justify-between items-center transition">
                        <span x-text="item"></span>
                        <i x-show="isSelected('goal', item)" class="fa-solid fa-circle-check text-xs"></i>
                    </button>
                </template>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button @click="prevStep()" class="text-theme-text-muted hover:text-theme-text text-xs flex items-center gap-1 font-normal">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                </button>
                <div class="flex items-center gap-2">
                    <button @click="nextStep()" class="text-theme-text-muted hover:text-theme-text text-xs px-3 py-2 font-normal">Skip</button>
                    <button @click="nextStep()" class="bg-brand text-primary text-xs font-normal px-5 py-3 rounded-full transition hover:opacity-90 inline-flex items-center gap-1.5">
                        Next <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 6: Custom Domain -->
        <div x-show="step === 6" x-transition>
            <div class="mb-5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 6</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">Custom domain <span class="text-theme-text-muted text-xs font-normal">(optional)</span></h2>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">Add your own branded domain or configure it later.</p>
            </div>

            <div>
                <label class="block text-xs text-theme-text mb-1.5 font-normal">Your Custom Domain</label>
                <input x-model="form.custom_domain" type="text"
                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted rounded-full px-4 py-3 text-xs focus:outline-none"
                       placeholder="e.g. academy.yourdomain.com">
                <p class="text-[11px] text-theme-text-muted mt-1.5 font-normal">You can also add or verify this at any time from your institute management console.</p>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button @click="prevStep()" class="text-theme-text-muted hover:text-theme-text text-xs flex items-center gap-1 font-normal">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                </button>
                <div class="flex items-center gap-2">
                    <button @click="nextStep()" class="text-theme-text-muted hover:text-theme-text text-xs px-3 py-2 font-normal">Skip</button>
                    <button @click="nextStep()" class="bg-brand text-primary text-xs font-normal px-5 py-3 rounded-full transition hover:opacity-90 inline-flex items-center gap-1.5">
                        Next <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 7: Admin Security & Password -->
        <div x-show="step === 7" x-transition>
            <div class="mb-3.5">
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 7 of 8 · Security Setup</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">Admin Security & Direct Password</h2>
                <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                    Configure direct administrative credentials for your isolated academy instance.
                </p>
            </div>

            <!-- Detailed Context Card -->
            <div class="bg-secondary rounded-lg p-3.5 mb-3 text-xs font-normal space-y-2">
                <div class="flex items-center justify-between pb-1.5 border-b border-primary">
                    <span class="text-theme-text-muted text-[11px]">Institute Name:</span>
                    <span class="text-theme-text font-semibold truncate max-w-[220px]" x-text="form.name || '—'"></span>
                </div>
                <div class="flex items-center justify-between pb-1.5 border-b border-primary">
                    <span class="text-theme-text-muted text-[11px]">Primary Admin:</span>
                    <span class="text-theme-text font-normal truncate max-w-[220px]">{{ Auth::guard('web')->user()->email }}</span>
                </div>
                <div class="flex items-center justify-between pb-1.5 border-b border-primary">
                    <span class="text-theme-text-muted text-[11px]">Assigned Role:</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal">
                        Tenant Administrator
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-theme-text-muted text-[11px]">Direct Login Portal:</span>
                    <span class="text-brand font-normal text-[11px] truncate max-w-[220px]" x-text="(form.subdomain || '') + '.{{ config('app.domain') }}/account/login'"></span>
                </div>
            </div>

            <!-- Informational Scope Notice -->
            <div class="bg-primary rounded-lg p-2.5 mb-3 text-[11px] text-theme-text-muted font-normal flex items-start gap-2">
                <i class="fa-solid fa-shield-halved text-brand text-xs mt-0.5 shrink-0"></i>
                <span>This password provides direct administrative login at <strong class="text-theme-text font-normal">/account/login</strong> inside your academy's isolated database.</span>
            </div>

            <!-- Password Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
                <div>
                    <label class="block text-xs text-theme-text mb-1 font-normal">Direct Admin Password *</label>
                    <div class="relative">
                        <input x-model="form.admin_password" :type="showPass ? 'text' : 'password'" required minlength="8"
                               placeholder="Min. 8 chars (letters & numbers)"
                               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted rounded-full px-4 py-2.5 pr-9 text-xs focus:outline-none">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-theme-text-muted hover:text-theme-text text-xs p-1">
                            <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-theme-text mb-1 font-normal">Confirm Password *</label>
                    <input x-model="form.admin_password_confirmation" type="password" required minlength="8"
                           placeholder="Repeat your password"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted rounded-full px-4 py-2.5 text-xs focus:outline-none">
                </div>
            </div>

            <!-- Live Password Security Feedback -->
            <div class="space-y-1 mb-2">
                <p x-show="passwordError" class="text-danger text-[11px] font-normal" x-text="passwordError"></p>
                <p x-show="form.admin_password && form.admin_password_confirmation && form.admin_password !== form.admin_password_confirmation" class="text-danger text-[11px] font-normal">
                    Passwords do not match.
                </p>
                <div x-show="isStrongPassword(form.admin_password) && form.admin_password === form.admin_password_confirmation" class="text-success text-[11px] font-normal flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Secure password confirmed.
                </div>
            </div>

            <div class="flex justify-between items-center mt-4">
                <button @click="prevStep()" class="text-theme-text-muted hover:text-theme-text text-xs flex items-center gap-1 font-normal">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                </button>
                <button @click="nextStep()"
                        :disabled="!isStrongPassword(form.admin_password) || form.admin_password !== form.admin_password_confirmation"
                        class="bg-brand text-primary text-xs font-normal px-5 py-2.5 rounded-full transition hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed inline-flex items-center gap-1.5">
                    Continue to Final Review <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </div>
        </div>

        <!-- STEP 8: Confirm & Launch -->
        <div x-show="step === 8" x-transition>
            <div class="mb-4 text-center">
                <div class="w-10 h-10 rounded-full bg-secondary text-theme-text flex items-center justify-center text-lg mx-auto mb-2">
                    <i class="fa-solid fa-graduation-cap text-brand"></i>
                </div>
                <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Step 8 of 8 · Ready</span>
                <h2 class="text-base font-semibold text-theme-text mt-0.5">Review & Launch Institute</h2>
                <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Review your details and provision your educational institute.</p>
            </div>

            <!-- Summary -->
            <div class="bg-secondary rounded-lg p-3.5 mb-4 space-y-2 text-xs font-normal">
                <div class="flex justify-between">
                    <span class="text-theme-text-muted">Institute Name:</span>
                    <span class="text-theme-text font-semibold" x-text="form.name || '—'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-theme-text-muted">Subdomain:</span>
                    <span class="text-theme-text" x-text="(form.subdomain || '—') + '.{{ config('app.domain') }}'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-theme-text-muted">Direct Login URL:</span>
                    <span class="text-brand font-normal text-[11px]" x-text="(form.subdomain || '') + '.{{ config('app.domain') }}/account/login'"></span>
                </div>
                <div class="flex justify-between" x-show="form.custom_domain">
                    <span class="text-theme-text-muted">Custom Domain:</span>
                    <span class="text-theme-text" x-text="form.custom_domain"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-theme-text-muted">Primary Admin:</span>
                    <span class="text-theme-text font-normal">{{ Auth::guard('web')->user()->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-theme-text-muted">Admin Password:</span>
                    <span class="text-success font-normal inline-flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Secured & Configured
                    </span>
                </div>
            </div>

            <form @submit.prevent="submitForm">
                @csrf
                <button type="submit"
                        class="w-full bg-brand text-primary font-normal py-2.5 px-6 rounded-full transition text-xs hover:opacity-90 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rocket text-[11px]"></i> Launch Institute
                </button>
            </form>

            <button @click="prevStep()" class="w-full text-center text-theme-text-muted hover:text-theme-text text-xs mt-2.5 transition font-normal">
                <i class="fa-solid fa-arrow-left text-[10px] mr-1"></i> Go back
            </button>
        </div>

    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
        <div class="bg-primary rounded-lg p-6 max-w-sm w-full text-center shadow-2xl">
            <div class="flex justify-center mb-4">
                <div class="w-8 h-8 border-2 border-primary/30 border-t-brand rounded-full animate-spin"></div>
            </div>
            <h2 class="text-sm font-semibold text-theme-text mb-1">Creating your institute...</h2>
            <p class="text-xs text-theme-text-muted font-normal">Setting up database, schema & subdomain routing...</p>
        </div>
    </div>
</div>

<script>
    function tenantWizard() {
        return {
            step: 1,
            totalSteps: 8,
            checking: false,
            available: null,
            loading: false,
            showPass: false,

            isStrongPassword(pwd) {
                if (!pwd || pwd.length < 8) return false;
                if (!/[a-zA-Z]/.test(pwd) || !/[0-9]/.test(pwd)) return false;
                const weakList = ['12345678', '123456789', 'password', 'password1', 'password123', 'admin123', 'qwerty123', '11111111'];
                if (weakList.includes(pwd.toLowerCase())) return false;
                if (/^(.)\1+$/.test(pwd)) return false;
                return true;
            },

            get passwordError() {
                const p = this.form.admin_password;
                if (!p) return '';
                if (p.length < 8) return 'Password must be at least 8 characters long.';
                if (!/[a-zA-Z]/.test(p) || !/[0-9]/.test(p)) return 'Password must include both letters and numbers.';
                const weakList = ['12345678', '123456789', 'password', 'password1', 'password123', 'admin123', 'qwerty123', '11111111'];
                if (weakList.includes(p.toLowerCase()) || /^(.)\1+$/.test(p)) {
                    return 'Password is too common or simple. Choose a stronger password.';
                }
                return '';
            },

            form: {
                name: '',
                subdomain: '',
                custom_domain: '',
                admin_password: '',
                admin_password_confirmation: '',
                org: [],
                offer: [],
                stage: null,
                goal: [],
            },

            submitForm() {
                this.loading = true;

                fetch("{{ route('tenants.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        name: this.form.name,
                        subdomain: this.form.subdomain,
                        custom_domain: this.form.custom_domain,
                        admin_password: this.form.admin_password
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message);
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Something went wrong. Please try again.");
                        this.loading = false;
                    });
            },

            orgTypes: [
                'Coaching Institute',
                'School',
                'College',
                'Online Platform',
                'Training Center',
                'University'
            ],

            offerings: [
                'Live Classes',
                'Recorded Videos',
                'Test Series',
                'Study Material',
                'Doubt Sessions',
                'Offline Classes'
            ],

            stages: [
                'Just getting started',
                'Already running offline',
                'Already teaching online',
                'Switching platform'
            ],

            goals: [
                'Manage students',
                'Sell courses',
                'Conduct live classes',
                'Build platform',
                'Track analytics'
            ],

            nextStep() {
                if (this.step < this.totalSteps) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            prevStep() {
                if (this.step > 1) this.step--;
            },

            toggle(type, value) {
                if (this.form[type].includes(value)) {
                    this.form[type] = this.form[type].filter(v => v !== value);
                } else {
                    this.form[type].push(value);
                }
            },

            isSelected(type, value) {
                return this.form[type].includes(value);
            },

            selectSingle(type, value) {
                this.form[type] = value;
            }
        }
    }
</script>

@endsection