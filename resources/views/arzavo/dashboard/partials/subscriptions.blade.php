<!-- Subscriptions Section -->
<section id="subscriptions" class="space-y-6">
    <!-- Section Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-primary">Subscriptions & Plans</h2>
            <p class="text-secondary">Manage your subscription plans and billing</p>
        </div>
        <button class="bg-accent text-invert px-6 py-3 border-rounded font-semibold hover-invert transition-all">
            <i class="fa-solid fa-crown mr-2"></i>
            Upgrade Plan
        </button>
    </div>

    <!-- Current Plan -->
    <div class="bg-primary border-primary border-rounded p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-star text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary">Starter Plan</h3>
                    <p class="text-secondary">Perfect for getting started</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-accent">₹999</div>
                <div class="text-tertiary text-sm">per month</div>
            </div>
        </div>

        <!-- Plan Features -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-secondary p-4 border-rounded text-center">
                <div class="text-2xl font-bold text-accent">1</div>
                <div class="text-tertiary text-sm">Tenant</div>
            </div>
            <div class="bg-secondary p-4 border-rounded text-center">
                <div class="text-2xl font-bold text-accent">100</div>
                <div class="text-tertiary text-sm">Students</div>
            </div>
            <div class="bg-secondary p-4 border-rounded text-center">
                <div class="text-2xl font-bold text-accent">5</div>
                <div class="text-tertiary text-sm">Courses</div>
            </div>
            <div class="bg-secondary p-4 border-rounded text-center">
                <div class="text-2xl font-bold text-accent">1GB</div>
                <div class="text-tertiary text-sm">Storage</div>
            </div>
        </div>

        <!-- Usage Progress -->
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-secondary">Tenants Used</span>
                    <span class="text-primary font-medium">{{ $tenants->count() }}/1</span>
                </div>
                <div class="w-full bg-tertiary border-rounded h-2">
                    <div class="bg-accent h-2 border-rounded" style="width: {{ ($tenants->count() / 1) * 100 }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-secondary">Students</span>
                    <span class="text-primary font-medium">67/100</span>
                </div>
                <div class="w-full bg-tertiary border-rounded h-2">
                    <div class="bg-green-500 h-2 border-rounded" style="width: 67%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-secondary">Storage Used</span>
                    <span class="text-primary font-medium">0.3GB/1GB</span>
                </div>
                <div class="w-full bg-tertiary border-rounded h-2">
                    <div class="bg-blue-500 h-2 border-rounded" style="width: 30%"></div>
                </div>
            </div>
        </div>

        <!-- Subscription Status -->
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-tertiary">
            <div class="flex items-center space-x-3">
                <div class="bg-green-100 text-green-600 w-8 h-8 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-check text-sm"></i>
                </div>
                <div>
                    <p class="text-primary font-medium">Active Subscription</p>
                    <p class="text-tertiary text-sm">Next billing: {{ now()->addMonth()->format('M j, Y') }}</p>
                </div>
            </div>
            <button class="text-accent text-sm font-medium hover:underline">
                Manage Subscription
            </button>
        </div>
    </div>

    <!-- Available Plans -->
    <div>
        <h3 class="text-xl font-bold text-primary mb-6">Available Plans</h3>
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Starter Plan -->
            <div class="bg-primary border-primary border-rounded p-6 relative">
                <div class="text-center mb-6">
                    <h4 class="text-xl font-bold text-primary mb-2">Starter</h4>
                    <div class="text-3xl font-bold text-accent mb-1">₹999</div>
                    <div class="text-tertiary text-sm">per month</div>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">1 Tenant</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Up to 100 students</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">5 courses</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">1GB storage</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Email support</span>
                    </li>
                </ul>

                <button disabled class="w-full bg-tertiary text-tertiary px-4 py-3 border-rounded font-medium cursor-not-allowed">
                    Current Plan
                </button>
            </div>

            <!-- Professional Plan -->
            <div class="bg-primary border-accent border-rounded p-6 relative transform scale-105">
                <!-- Popular Badge -->
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-accent text-invert px-4 py-1 border-rounded text-sm font-semibold">Most Popular</span>
                </div>

                <div class="text-center mb-6 mt-4">
                    <h4 class="text-xl font-bold text-primary mb-2">Professional</h4>
                    <div class="text-3xl font-bold text-accent mb-1">₹2,499</div>
                    <div class="text-tertiary text-sm">per month</div>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Up to 5 Tenants</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Up to 500 students</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Unlimited courses</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">10GB storage</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Priority support</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Custom domain</span>
                    </li>
                </ul>

                <button class="w-full bg-accent text-invert px-4 py-3 border-rounded font-medium hover-invert transition-all">
                    Upgrade to Professional
                </button>
            </div>

            <!-- Enterprise Plan -->
            <div class="bg-primary border-primary border-rounded p-6 relative">
                <div class="text-center mb-6">
                    <h4 class="text-xl font-bold text-primary mb-2">Enterprise</h4>
                    <div class="text-3xl font-bold text-accent mb-1">₹4,999</div>
                    <div class="text-tertiary text-sm">per month</div>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Unlimited Tenants</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Unlimited students</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Unlimited courses</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">Unlimited storage</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">24/7 dedicated support</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check text-accent mr-3"></i>
                        <span class="text-secondary">White-label solution</span>
                    </li>
                </ul>

                <button class="w-full bg-secondary text-primary border-primary px-4 py-3 border-rounded font-medium hover-primary transition-all">
                    Contact Sales
                </button>
            </div>
        </div>
    </div>

    <!-- Billing Cycle Toggle -->
    <div class="bg-secondary border-primary border-rounded p-6">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-semibold text-primary mb-2">Save with Annual Billing</h4>
                <p class="text-secondary text-sm">Get 2 months free when you pay annually</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-secondary">Monthly</span>
                <div class="relative">
                    <input type="checkbox" id="billingToggle" class="sr-only">
                    <label for="billingToggle" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <div class="block bg-tertiary w-14 h-8 border-rounded"></div>
                            <div class="dot absolute left-1 top-1 bg-primary w-6 h-6 border-rounded transition"></div>
                        </div>
                    </label>
                </div>
                <span class="text-secondary">Annual <span class="text-accent font-semibold">(Save 20%)</span></span>
            </div>
        </div>
    </div>
</section>