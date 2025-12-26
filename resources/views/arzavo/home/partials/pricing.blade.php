<!-- Pricing Section -->
<section id="pricing" class="bg-secondary py-24 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-3">
        <div class="absolute top-16 left-16 w-28 h-28 bg-accent border-rounded transform rotate-12"></div>
        <div class="absolute top-40 right-24 w-20 h-20 bg-accent-secondary border-rounded transform -rotate-12"></div>
        <div class="absolute bottom-24 left-1/4 w-16 h-16 bg-accent border-rounded transform rotate-45"></div>
        <div class="absolute bottom-48 right-16 w-24 h-24 bg-accent-secondary border-rounded transform -rotate-30"></div>
    </div>
    
    <div class="container relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <h2 class="text-5xl lg:text-6xl font-bold text-primary mb-8 ">
                Simple, Transparent 
                <span class="text-accent relative">
                    Pricing
                    <div class="absolute -bottom-2 left-0 w-full h-1 bg-accent opacity-30 border-rounded"></div>
                </span>
            </h2>
            <p class="text-xl lg:text-2xl text-secondary max-w-4xl mx-auto mb-10 leading-relaxed">
                Choose the perfect plan for your educational institution. Start with our free trial and scale as you grow.
            </p>
            
            <!-- Billing Toggle -->
            <div class="flex items-center justify-center space-x-6 mb-16">
                <span class="text-secondary font-semibold text-lg">Monthly</span>
                <div class="relative">
                    <input type="checkbox" id="billingToggle" class="sr-only" onchange="toggleBilling()">
                    <label for="billingToggle" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <div class="block bg-tertiary w-16 h-9 border-rounded shadow-inner"></div>
                            <div class="dot absolute left-1 top-1 bg-primary w-7 h-7 border-rounded transition shadow-lg"></div>
                        </div>
                    </label>
                </div>
                <span class="text-secondary font-semibold text-lg">Yearly <span class="text-accent font-bold bg-accent-subtle px-3 py-1 border-rounded text-sm ml-2">Save 20%</span></span>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-10 max-w-7xl mx-auto">
            <!-- Starter Plan -->
            <div class="bg-primary border-primary border-rounded p-10 relative lg:shadow-xl hover-primary transition-all duration-500 transform hover:scale-105">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-primary mb-3 ">Starter</h3>
                    <p class="text-secondary mb-8 text-lg">Perfect for small schools and coaching centers</p>
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-accent " id="starter-price">₹999</span>
                        <span class="text-secondary text-xl">/month</span>
                    </div>
                </div>

                <ul class="space-y-5 mb-10">
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Up to 100 students</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">5 courses</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Basic customization</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Subdomain included</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Email support</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">1GB storage</span>
                    </li>
                </ul>

                <a href="{{ route('register.form') }}" class="w-full bg-secondary text-primary border-primary px-8 py-4 border-rounded font-bold text-center hover-primary transition-all duration-300 block text-lg shadow-lg">
                    Start Free Trial
                </a>
            </div>

            <!-- Professional Plan (Popular) -->
            <div class="bg-primary border-accent border-rounded p-10 mt-4 sm:mt-0 relative transform lg:scale-110 lg:shadow-2xl">
                <!-- Popular Badge -->
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-accent text-invert px-8 py-3 border-rounded font-bold text-lg shadow-xl">Most Popular</span>
                </div>

                <div class="text-center mb-10 mt-6">
                    <h3 class="text-3xl font-bold text-primary mb-3 ">Professional</h3>
                    <p class="text-secondary mb-8 text-lg">Ideal for growing institutions</p>
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-accent" id="professional-price">₹2,499</span>
                        <span class="text-secondary text-xl">/month</span>
                    </div>
                </div>

                <ul class="space-y-5 mb-10">
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Up to 500 students</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Unlimited courses</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Advanced customization</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Custom domain</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Priority support</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">10GB storage</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Advanced analytics</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Payment gateway integration</span>
                    </li>
                </ul>

                <a href="{{ route('register.form') }}" class="w-full bg-accent text-invert px-8 py-4 border-rounded font-bold text-center hover-invert transition-all duration-300 block text-lg shadow-xl transform hover:scale-105">
                    Start Free Trial
                </a>
            </div>

            <!-- Enterprise Plan -->
            <div class="bg-primary border-primary border-rounded p-10 relative lg:shadow-xl hover-primary transition-all duration-500 transform hover:scale-105">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-primary mb-3 ">Enterprise</h3>
                    <p class="text-secondary mb-8 text-lg">For large educational institutions</p>
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-accent " id="enterprise-price">₹4,999</span>
                        <span class="text-secondary text-xl">/month</span>
                    </div>
                </div>

                <ul class="space-y-5 mb-10">
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Unlimited students</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Unlimited courses</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">White-label solution</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Multiple custom domains</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">24/7 dedicated support</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Unlimited storage</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Advanced integrations</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-accent mr-4 text-xl"></i>
                        <span class="text-secondary font-medium">Custom development</span>
                    </li>
                </ul>

                <a href="#contact" class="w-full bg-secondary text-primary border-primary px-8 py-4 border-rounded font-bold text-center hover-primary transition-all duration-300 block text-lg shadow-lg">
                    Contact Sales
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-32">
            <h3 class="text-3xl font-bold text-primary text-center mb-12">Frequently Asked Questions</h3>
            <div class="grid md:grid-cols-2 gap-8 mx-auto">
                <div class="bg-primary border-primary border-rounded p-6">
                    <h4 class="text-xl font-semibold text-primary mb-3">Is there a free trial?</h4>
                    <p class="text-secondary">Yes! We offer a 14-day free trial for all plans. No credit card required to get started.</p>
                </div>
                <div class="bg-primary border-primary border-rounded p-6">
                    <h4 class="text-xl font-semibold text-primary mb-3">Can I change plans anytime?</h4>
                    <p class="text-secondary">Absolutely! You can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                </div>
                <div class="bg-primary border-primary border-rounded p-6">
                    <h4 class="text-xl font-semibold text-primary mb-3">What payment methods do you accept?</h4>
                    <p class="text-secondary">We accept all major credit cards, debit cards, UPI, and net banking for Indian customers.</p>
                </div>
                <div class="bg-primary border-primary border-rounded p-6">
                    <h4 class="text-xl font-semibold text-primary mb-3">Is my data secure?</h4>
                    <p class="text-secondary">Yes! We use enterprise-grade security with complete data isolation between tenants and regular backups.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleBilling() {
    const toggle = document.getElementById('billingToggle');
    const dot = document.querySelector('.dot');
    const starterPrice = document.getElementById('starter-price');
    const professionalPrice = document.getElementById('professional-price');
    const enterprisePrice = document.getElementById('enterprise-price');
    
    if (toggle.checked) {
        // Yearly pricing (20% discount)
        dot.style.transform = 'translateX(24px)';
        starterPrice.textContent = '₹7,992';
        professionalPrice.textContent = '₹19,992';
        enterprisePrice.textContent = '₹39,992';
    } else {
        // Monthly pricing
        dot.style.transform = 'translateX(0px)';
        starterPrice.textContent = '₹999';
        professionalPrice.textContent = '₹2,499';
        enterprisePrice.textContent = '₹4,999';
    }
}
</script>