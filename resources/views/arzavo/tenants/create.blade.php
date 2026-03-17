@extends('layouts.app')
@section('title', 'Create Tenant - ' . config('app.name'))

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-linear-to-br from-black via-[#230147] to-[#0a001b] px-4">

        <div x-data="tenantWizard()" class="w-full max-w-2xl bg-primary border-primary border-rounded p-8 shadow-2xl">

            <!-- HEADER -->
            <div class="mb-6">
                <p class="text-xs text-tertiary mb-1">
                    Step <span x-text="step"></span> of <span x-text="totalSteps"></span>
                </p>

                <div class="w-full bg-tertiary h-1">
                    <div class="bg-invert h-1 transition-all duration-500"
                        :style="'width:' + (step / totalSteps * 100) + '%'"></div>
                </div>
            </div>

            <!-- STEP 1 -->
            <div x-show="step === 1" x-transition>
                <h2 class="text-2xl font-bold mb-2">Let’s build your platform 🚀</h2>
                <p class="text-sm text-tertiary mb-5">
                    Start with your institute name and a unique URL. This will be your identity.
                </p>

                <input x-model="form.name" type="text" placeholder="Institute Name"
                    class="w-full border-primary border-rounded p-3 mb-3">

                <div class="relative">
                    <input x-model="form.subdomain" @input="
                                                        form.subdomain = form.subdomain.toLowerCase().replace(/[^a-z0-9-]/g, '');

                                                        if(form.subdomain.length >= 3){
                                                            checking = true;

                                                            fetch(`/check-subdomain?subdomain=${form.subdomain}`)
                                                                .then(res => res.json())
                                                                .then(data => {
                                                                    available = data.available;
                                                                    checking = false;
                                                                });
                                                        } else {
                                                            available = null;
                                                        }
                                                    " type="text" placeholder="yourinstitute" class="w-full border-primary border-rounded p-3 pr-28">
                    <span class="absolute right-0 top-0 p-3 text-tertiary">
                        .{{ config('app.domain') }}
                    </span>
                </div>
                <p class="text-xs mt-2">
                    <span x-show="checking" class="text-tertiary">Checking availability...</span>

                    <span x-show="available === true" class="text-green-500">
                        ✔ Available
                    </span>

                    <span x-show="available === false" class="text-red-500">
                        ✖ Already taken
                    </span>
                </p>
                <p class="text-xs text-tertiary mt-2">
                    Only lowercase letters, numbers and hyphens allowed. No spaces or symbols.
                </p>

                <button @click="nextStep()" :disabled="!form.name || !form.subdomain || available === false"
                    class="mt-5 w-full bg-invert text-invert p-3 border-rounded disabled:opacity-50">
                    Continue
                </button>
            </div>

            <!-- STEP 2 -->
            <div x-show="step === 2" x-transition>
                <h2 class="text-2xl font-bold mb-2">Tell us about your organization</h2>
                <p class="text-sm text-tertiary mb-5">
                    This helps us personalize your dashboard experience.
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <template x-for="item in orgTypes">
                        <button @click="toggle('org', item)"
                            :class="isSelected('org', item) ? 'bg-invert text-invert' : 'border-primary hover-primary'"
                            class="border-rounded p-3 text-sm text-left transition">
                            <span x-text="item"></span>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="bg-invert text-invert px-4 py-2 border-rounded"><i
                            class="fa-solid fa-angles-left mr-1"></i> Back</button>
                    <button @click="nextStep()" class="bg-invert text-invert px-4 py-2 border-rounded">Next <i
                            class="fa-solid fa-angles-right ml-1"></i></button>
                </div>

                <button @click="nextStep()" class="text-base text-tertiary mt-4 italic">Skip this step</button>
            </div>

            <!-- STEP 3 -->
            <div x-show="step === 3" x-transition>
                <h2 class="text-2xl font-bold mb-2">What will you offer?</h2>
                <p class="text-sm text-tertiary mb-5">
                    Choose everything you plan to provide.
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <template x-for="item in offerings">
                        <button @click="toggle('offer', item)"
                            :class="isSelected('offer', item) ? 'bg-invert text-invert' : 'border-primary hover-primary'"
                            class="border-rounded p-3 text-sm text-left">
                            <span x-text="item"></span>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="bg-invert text-invert px-4 py-2 border-rounded"><i
                            class="fa-solid fa-angles-left mr-1"></i> Back</button>
                    <button @click="nextStep()" class="bg-invert text-invert px-4 py-2 border-rounded">Next <i
                            class="fa-solid fa-angles-right ml-1"></i></button>
                </div>

                <button @click="nextStep()" class="text-base text-tertiary mt-4 italic">Skip this step</button>
            </div>

            <!-- STEP 4 -->
            <div x-show="step === 4" x-transition>
                <h2 class="text-2xl font-bold mb-2">Where are you starting from?</h2>
                <p class="text-sm text-tertiary mb-5">
                    We’ll tailor your setup based on this.
                </p>

                <div class="grid grid-cols-1 gap-3">
                    <template x-for="item in stages">
                        <button @click="selectSingle('stage', item)"
                            :class="form.stage === item ? 'bg-invert text-invert' : 'border-primary hover-primary'"
                            class="border-rounded p-3 text-sm text-left">
                            <span x-text="item"></span>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="bg-invert text-invert px-4 py-2 border-rounded"><i
                            class="fa-solid fa-angles-left mr-1"></i> Back</button>
                    <button @click="nextStep()" class="bg-invert text-invert px-4 py-2 border-rounded">Next <i
                            class="fa-solid fa-angles-right ml-1"></i></button>
                </div>

                <button @click="nextStep()" class="text-base text-tertiary mt-4 italic">Skip this step</button>
            </div>

            <!-- STEP 5 -->
            <div x-show="step === 5" x-transition>
                <h2 class="text-2xl font-bold mb-2">What’s your goal?</h2>
                <p class="text-sm text-tertiary mb-5">
                    This helps us optimize your dashboard.
                </p>

                <div class="grid grid-cols-1 gap-3">
                    <template x-for="item in goals">
                        <button @click="toggle('goal', item)" :class="isSelected('goal', item) 
                                                                            ? 'bg-invert text-invert border-invert' 
                                                                            : 'border-primary hover-primary'"
                            class="border-rounded p-3 text-sm text-left flex justify-between items-center">

                            <span x-text="item"></span>

                            <!-- tick mark -->
                            <span x-show="isSelected('goal', item)">✔</span>
                        </button>
                    </template>
                </div>

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="bg-invert text-invert px-4 py-2 border-rounded">
                        <i class="fa-solid fa-angles-left mr-1"></i> Back
                    </button>

                    <button @click="nextStep()" class="bg-invert text-invert px-4 py-2 border-rounded">
                        Next <i class="fa-solid fa-angles-right ml-1"></i>
                    </button>
                </div>

                <button @click="nextStep()" class="text-base text-tertiary mt-4 italic">
                    Skip this step
                </button>
            </div>

            <!-- STEP 6 -->
            <div x-show="step === 6" x-transition>
                <h2 class="text-2xl font-bold mb-2">Custom domain (optional)</h2>
                <p class="text-sm text-tertiary mb-5">
                    Add your own domain for a professional touch.
                </p>

                <input x-model="form.custom_domain" type="text" class="w-full border-primary border-rounded p-3"
                    placeholder="yourdomain.com">

                <div class="flex justify-between mt-6">
                    <button @click="prevStep()" class="bg-invert text-invert px-4 py-2 border-rounded"><i
                            class="fa-solid fa-angles-left mr-1"></i> Back</button>
                    <button @click="nextStep()" class="bg-invert text-invert px-4 py-2 border-rounded">Next <i
                            class="fa-solid fa-angles-right ml-1"></i></button>
                </div>

                <button @click="nextStep()" class="text-base text-tertiary mt-4 italic">Skip this step</button>
            </div>

            <!-- FINAL -->
            <div x-show="step === 7" x-transition>
                <h2 class="text-2xl font-bold mb-2">You're all set 🎉</h2>
                <p class="text-sm text-tertiary mb-5">
                    Your platform is ready. Let’s launch your workspace.
                </p>

                <form @submit.prevent="submitForm">
                    @csrf

                    <input type="hidden" name="name" :value="form.name">
                    <input type="hidden" name="subdomain" :value="form.subdomain">
                    <input type="hidden" name="custom_domain" :value="form.custom_domain">

                    <button type="submit" class="w-full bg-invert text-invert p-3 border-rounded">
                        Create Tenant
                    </button>
                </form>
            </div>

            <div x-show="loading" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">

                <div class="bg-primary border-primary border-rounded p-8 text-center w-80">

                    <h2 class="text-lg font-bold mb-4">Creating your workspace...</h2>

                    <!-- Animated Loader -->
                    <div class="w-full bg-tertiary h-2 border-rounded overflow-hidden mb-4">
                        <div class="bg-invert h-2 animate-pulse w-full"></div>
                    </div>

                    <p class="text-sm text-tertiary">
                        Setting up your dashboard, database & environment...
                    </p>

                </div>
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
                // 👇 yahi daalna hai
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
                            window.location.href = data.redirect;
                        })
                        .catch(err => {
                            this.loading = false;
                            alert("Failed to create tenant. Try again.");
                        });
                },

                orgTypes: [
                    'Coaching Institute',
                    'School',
                    'College',
                    'Online Platform',
                    'Training Center',
                    'Personal Brand'
                ],

                offerings: [
                    'Live Classes',
                    'Recorded Courses',
                    'Notes & PDFs',
                    'Test Series',
                    'Assignments',
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