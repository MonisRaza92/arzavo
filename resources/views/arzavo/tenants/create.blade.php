@extends('layouts.app')
@section('title', 'Create Workspace - ' . config('app.name'))

@section('content')

<div x-data="tenantWizard()" class="min-h-screen flex items-center justify-center relative bg-gradient-to-br from-[#0a001b] via-[#1a0035] to-[#080012] px-4 py-10">

    <!-- Decorative floating orbs -->
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-purple-600 opacity-10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-600 opacity-10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Back Link -->
    <a href="{{ route('tenants.index') }}"
       class="absolute top-6 left-6 text-white/50 hover:text-white text-sm flex items-center gap-2 transition">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Workspaces
    </a>

    <!-- Wizard Card -->
    <div class="w-full max-w-xl z-10">

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="{{ route('home') }}">
                <img id="logo" src="{{ media('images/logo/arzavo-white.png') }}" alt="Arzavo"
                     class="h-10 object-contain"
                     onerror="this.src='{{ media('images/logo/arzavo-dark.png') }}'">
            </a>
        </div>

        <!-- Progress Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center text-xs text-white/40 mb-2">
                <span>Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
                <span x-text="Math.round((step / totalSteps) * 100) + '% complete'"></span>
            </div>
            <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-purple-400 to-indigo-400 h-1 rounded-full transition-all duration-500"
                     :style="'width:' + (step / totalSteps * 100) + '%'"></div>
            </div>
            <!-- Step dots -->
            <div class="flex justify-center gap-2 mt-4">
                <template x-for="i in totalSteps" :key="i">
                    <div :class="i <= step ? 'bg-purple-400 scale-125' : 'bg-white/20'"
                         class="w-2 h-2 rounded-full transition-all duration-300"></div>
                </template>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-2xl text-white">

            <!-- STEP 1: Name & Subdomain -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">Step 1</span>
                    <h2 class="text-2xl font-bold mt-1">Let's build your platform 🚀</h2>
                    <p class="text-sm text-white/50 mt-1">Start with your institute name and a unique URL.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-white/50 mb-1.5">Institute / Organization Name</label>
                        <input x-model="form.name" type="text" placeholder="e.g. Al-Shifa Academy"
                               class="w-full bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-purple-400 transition">
                    </div>

                    <div>
                        <label class="block text-xs text-white/50 mb-1.5">Workspace URL (Subdomain)</label>
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
                               class="w-full bg-white/10 border border-white/15 rounded-xl px-4 py-3 pr-40 text-white placeholder-white/30 focus:outline-none focus:border-purple-400 transition">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-white/30">.{{ config('app.domain') }}</span>
                        </div>
                        <p class="text-xs mt-2">
                            <span x-show="checking" class="text-white/40"><i class="fa-solid fa-spinner animate-spin mr-1"></i>Checking...</span>
                            <span x-show="available === true" class="text-green-400"><i class="fa-solid fa-circle-check mr-1"></i>Available</span>
                            <span x-show="available === false" class="text-red-400"><i class="fa-solid fa-circle-xmark mr-1"></i>Already taken</span>
                        </p>
                        <p class="text-xs text-white/30 mt-1">Lowercase letters, numbers and hyphens only.</p>
                    </div>
                </div>

                <button @click="nextStep()" :disabled="!form.name || !form.subdomain || available === false"
                        class="mt-6 w-full bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white font-semibold py-3 rounded-xl transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Continue <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>

            <!-- STEP 2: Organization Type -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">Step 2</span>
                    <h2 class="text-2xl font-bold mt-1">Tell us about your organization</h2>
                    <p class="text-sm text-white/50 mt-1">This helps us personalize your dashboard experience.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <template x-for="item in orgTypes">
                        <button @click="toggle('org', item)"
                                :class="isSelected('org', item) ? 'bg-purple-500/30 border-purple-400 text-white' : 'bg-white/5 border-white/10 text-white/60 hover:border-white/30'"
                                class="border rounded-xl p-3 text-sm text-left transition">
                            <span x-text="item"></span>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="text-white/50 hover:text-white text-sm flex items-center gap-1 transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Back
                    </button>
                    <div class="flex gap-2">
                        <button @click="nextStep()" class="text-white/30 hover:text-white text-sm transition">Skip</button>
                        <button @click="nextStep()" class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                            Next <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Offerings -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">Step 3</span>
                    <h2 class="text-2xl font-bold mt-1">What will you offer?</h2>
                    <p class="text-sm text-white/50 mt-1">Choose everything you plan to provide.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <template x-for="item in offerings">
                        <button @click="toggle('offer', item)"
                                :class="isSelected('offer', item) ? 'bg-purple-500/30 border-purple-400 text-white' : 'bg-white/5 border-white/10 text-white/60 hover:border-white/30'"
                                class="border rounded-xl p-3 text-sm text-left transition">
                            <span x-text="item"></span>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="text-white/50 hover:text-white text-sm flex items-center gap-1 transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Back
                    </button>
                    <div class="flex gap-2">
                        <button @click="nextStep()" class="text-white/30 hover:text-white text-sm transition">Skip</button>
                        <button @click="nextStep()" class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                            Next <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Stage -->
            <div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">Step 4</span>
                    <h2 class="text-2xl font-bold mt-1">Where are you starting from?</h2>
                    <p class="text-sm text-white/50 mt-1">We'll tailor your setup based on this.</p>
                </div>

                <div class="space-y-3">
                    <template x-for="item in stages">
                        <button @click="selectSingle('stage', item)"
                                :class="form.stage === item ? 'bg-purple-500/30 border-purple-400 text-white' : 'bg-white/5 border-white/10 text-white/60 hover:border-white/30'"
                                class="w-full border rounded-xl p-3 text-sm text-left flex justify-between items-center transition">
                            <span x-text="item"></span>
                            <i x-show="form.stage === item" class="fa-solid fa-circle-check text-purple-400 text-xs"></i>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="text-white/50 hover:text-white text-sm flex items-center gap-1 transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Back
                    </button>
                    <div class="flex gap-2">
                        <button @click="nextStep()" class="text-white/30 hover:text-white text-sm transition">Skip</button>
                        <button @click="nextStep()" class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                            Next <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 5: Goals -->
            <div x-show="step === 5" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">Step 5</span>
                    <h2 class="text-2xl font-bold mt-1">What's your goal?</h2>
                    <p class="text-sm text-white/50 mt-1">This helps us optimize your dashboard.</p>
                </div>

                <div class="space-y-3">
                    <template x-for="item in goals">
                        <button @click="toggle('goal', item)"
                                :class="isSelected('goal', item) ? 'bg-purple-500/30 border-purple-400 text-white' : 'bg-white/5 border-white/10 text-white/60 hover:border-white/30'"
                                class="w-full border rounded-xl p-3 text-sm text-left flex justify-between items-center transition">
                            <span x-text="item"></span>
                            <i x-show="isSelected('goal', item)" class="fa-solid fa-circle-check text-purple-400 text-xs"></i>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="text-white/50 hover:text-white text-sm flex items-center gap-1 transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Back
                    </button>
                    <div class="flex gap-2">
                        <button @click="nextStep()" class="text-white/30 hover:text-white text-sm transition">Skip</button>
                        <button @click="nextStep()" class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                            Next <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 6: Custom Domain -->
            <div x-show="step === 6" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">Step 6</span>
                    <h2 class="text-2xl font-bold mt-1">Custom domain <span class="text-white/30 text-base font-normal">(optional)</span></h2>
                    <p class="text-sm text-white/50 mt-1">Add your own domain for a professional touch.</p>
                </div>

                <div>
                    <label class="block text-xs text-white/50 mb-1.5">Your Custom Domain</label>
                    <input x-model="form.custom_domain" type="text"
                           class="w-full bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-purple-400 transition"
                           placeholder="e.g. academy.yourdomain.com">
                    <p class="text-xs text-white/30 mt-2">You can also add or change this later from your workspace settings.</p>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="text-white/50 hover:text-white text-sm flex items-center gap-1 transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Back
                    </button>
                    <div class="flex gap-2">
                        <button @click="nextStep()" class="text-white/30 hover:text-white text-sm transition">Skip</button>
                        <button @click="nextStep()" class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                            Next <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 7: Confirm & Launch -->
            <div x-show="step === 7" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6 text-center">
                    <div class="text-5xl mb-4">🎉</div>
                    <span class="text-xs font-semibold text-purple-300 uppercase tracking-widest">All Set</span>
                    <h2 class="text-2xl font-bold mt-1">You're ready to launch!</h2>
                    <p class="text-sm text-white/50 mt-1">Review and create your workspace.</p>
                </div>

                <!-- Summary -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-white/40">Name:</span>
                        <span class="font-semibold" x-text="form.name || '—'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/40">Subdomain:</span>
                        <span class="font-mono" x-text="(form.subdomain || '—') + '.{{ config('app.domain') }}'"></span>
                    </div>
                    <div class="flex justify-between" x-show="form.custom_domain">
                        <span class="text-white/40">Custom Domain:</span>
                        <span class="font-mono" x-text="form.custom_domain"></span>
                    </div>
                </div>

                <form @submit.prevent="submitForm">
                    @csrf
                    <input type="hidden" name="name" :value="form.name">
                    <input type="hidden" name="subdomain" :value="form.subdomain">
                    <input type="hidden" name="custom_domain" :value="form.custom_domain">

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white font-bold py-3.5 rounded-xl transition text-base">
                        <i class="fa-solid fa-rocket mr-2"></i> Launch Workspace
                    </button>
                </form>

                <button @click="prevStep()" class="w-full text-center text-white/30 hover:text-white text-sm mt-3 transition">
                    <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Go back
                </button>
            </div>

        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 border-4 border-purple-400/20 border-t-purple-400 rounded-full animate-spin"></div>
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Creating your workspace...</h2>
            <p class="text-sm text-white/40">Setting up your dashboard, database & environment...</p>
            <div class="mt-6 w-64 bg-white/10 h-1 rounded-full overflow-hidden mx-auto">
                <div class="bg-gradient-to-r from-purple-400 to-indigo-400 h-1 rounded-full animate-pulse w-3/4"></div>
            </div>
        </div>
    </div>

    <script>
        function tenantWizard() {
            return {
                step: 1,
                totalSteps: 7,
                checking: false,
                available: null,
                loading: false,

                form: {
                    name: '',
                    subdomain: '',
                    custom_domain: '',
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
                            custom_domain: this.form.custom_domain
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