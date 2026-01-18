<!-- Billing Section -->
<section id="billing" class="space-y-6">
    <!-- Section Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-primary">Billing & Payments</h2>
            <p class="text-secondary">Manage your payment methods and billing history</p>
        </div>
        <button class="bg-accent text-invert px-6 py-3 border-rounded font-semibold hover-invert transition-all">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Payment Method
        </button>
    </div>

    <!-- Current Bill -->
    <div class="bg-primary border-primary border-rounded p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary">Current Bill</h3>
            <span class="bg-green-100 text-green-800 px-3 py-1 border-rounded text-sm font-semibold">Paid</span>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Bill Summary -->
            <div>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-secondary">Starter Plan</span>
                        <span class="text-primary font-medium">₹999.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary">GST (18%)</span>
                        <span class="text-primary font-medium">₹179.82</span>
                    </div>
                    <div class="border-t border-tertiary pt-4">
                        <div class="flex justify-between">
                            <span class="text-primary font-semibold">Total Amount</span>
                            <span class="text-accent font-bold text-xl">₹1,178.82</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Period -->
            <div>
                <h4 class="text-lg font-semibold text-primary mb-4">Billing Period</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-calendar text-tertiary"></i>
                        <div>
                            <p class="text-secondary text-sm">Current Period</p>
                            <p class="text-primary font-medium">{{ now()->format('M j') }} - {{ now()->addMonth()->format('M j, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-credit-card text-tertiary"></i>
                        <div>
                            <p class="text-secondary text-sm">Next Payment</p>
                            <p class="text-primary font-medium">{{ now()->addMonth()->format('M j, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-repeat text-tertiary"></i>
                        <div>
                            <p class="text-secondary text-sm">Billing Cycle</p>
                            <p class="text-primary font-medium">Monthly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-primary border-primary border-rounded p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary">Payment Methods</h3>
            <button class="text-accent text-sm font-medium hover:underline">
                <i class="fa-solid fa-plus mr-1"></i>
                Add New Method
            </button>
        </div>

        <div class="space-y-4">
            <!-- Credit Card -->
            <div class="bg-secondary border-primary border-rounded p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-accent text-invert w-12 h-12 border-rounded flex items-center justify-center">
                        <i class="fa-brands fa-cc-visa text-xl"></i>
                    </div>
                    <div>
                        <p class="text-primary font-medium">•••• •••• •••• 4242</p>
                        <p class="text-tertiary text-sm">Expires 12/25</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-green-100 text-green-800 px-2 py-1 border-rounded text-xs font-semibold">Default</span>
                    <button class="text-tertiary hover:text-primary">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>

            <!-- UPI -->
            <div class="bg-secondary border-primary border-rounded p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-500 text-white w-12 h-12 border-rounded flex items-center justify-center">
                        <i class="fa-solid fa-mobile-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-primary font-medium">user@paytm</p>
                        <p class="text-tertiary text-sm">UPI Payment</p>
                    </div>
                </div>
                <button class="text-tertiary hover:text-primary">
                    <i class="fa-solid fa-ellipsis-v"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Billing History -->
    <div class="bg-primary border-primary border-rounded p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary">Billing History</h3>
            <button class="text-accent text-sm font-medium hover:underline">
                Download All Invoices
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-tertiary">
                        <th class="text-left text-secondary text-sm font-medium py-3">Invoice</th>
                        <th class="text-left text-secondary text-sm font-medium py-3">Date</th>
                        <th class="text-left text-secondary text-sm font-medium py-3">Amount</th>
                        <th class="text-left text-secondary text-sm font-medium py-3">Status</th>
                        <th class="text-left text-secondary text-sm font-medium py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tertiary">
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-file-invoice text-tertiary"></i>
                                <span class="text-primary font-medium">#INV-2024-001</span>
                            </div>
                        </td>
                        <td class="py-4 text-secondary">{{ now()->format('M j, Y') }}</td>
                        <td class="py-4 text-primary font-medium">₹1,178.82</td>
                        <td class="py-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 border-rounded text-xs font-semibold">Paid</span>
                        </td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <button class="text-accent text-sm hover:underline">Download</button>
                                <button class="text-accent text-sm hover:underline">View</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-file-invoice text-tertiary"></i>
                                <span class="text-primary font-medium">#INV-2023-012</span>
                            </div>
                        </td>
                        <td class="py-4 text-secondary">{{ now()->subMonth()->format('M j, Y') }}</td>
                        <td class="py-4 text-primary font-medium">₹1,178.82</td>
                        <td class="py-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 border-rounded text-xs font-semibold">Paid</span>
                        </td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <button class="text-accent text-sm hover:underline">Download</button>
                                <button class="text-accent text-sm hover:underline">View</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-file-invoice text-tertiary"></i>
                                <span class="text-primary font-medium">#INV-2023-011</span>
                            </div>
                        </td>
                        <td class="py-4 text-secondary">{{ now()->subMonths(2)->format('M j, Y') }}</td>
                        <td class="py-4 text-primary font-medium">₹1,178.82</td>
                        <td class="py-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 border-rounded text-xs font-semibold">Paid</span>
                        </td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <button class="text-accent text-sm hover:underline">Download</button>
                                <button class="text-accent text-sm hover:underline">View</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Billing Settings -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Auto-renewal -->
        <div class="bg-primary border-primary border-rounded p-6">
            <h4 class="text-lg font-semibold text-primary mb-4">Auto-renewal Settings</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-primary font-medium">Auto-renewal</p>
                        <p class="text-tertiary text-sm">Automatically renew subscription</p>
                    </div>
                    <div class="relative">
                        <input type="checkbox" id="autoRenewal" class="sr-only" checked>
                        <label for="autoRenewal" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <div class="block bg-accent w-14 h-8 border-rounded"></div>
                                <div class="dot absolute right-1 top-1 bg-primary w-6 h-6 border-rounded transition"></div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-primary font-medium">Email notifications</p>
                        <p class="text-tertiary text-sm">Get billing reminders via email</p>
                    </div>
                    <div class="relative">
                        <input type="checkbox" id="emailNotifications" class="sr-only" checked>
                        <label for="emailNotifications" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <div class="block bg-accent w-14 h-8 border-rounded"></div>
                                <div class="dot absolute right-1 top-1 bg-primary w-6 h-6 border-rounded transition"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Address -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-primary">Billing Address</h4>
                <button class="text-accent text-sm font-medium hover:underline">Edit</button>
            </div>
            <div class="space-y-2 text-sm">
                <p class="text-primary font-medium">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</p>
                <p class="text-secondary">123 Business Street</p>
                <p class="text-secondary">Mumbai, Maharashtra 400001</p>
                <p class="text-secondary">India</p>
                <p class="text-secondary">GST: 27XXXXX1234X1ZX</p>
            </div>
        </div>
    </div>

    <!-- Usage-based Billing Info -->
    <div class="bg-accent-subtle border-accent border-rounded p-6">
        <div class="flex items-start space-x-4">
            <div class="bg-accent text-invert w-12 h-12 border-rounded flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-info"></i>
            </div>
            <div>
                <h4 class="text-accent font-semibold mb-2">Usage-based Billing</h4>
                <p class="text-accent text-sm mb-3">
                    Your subscription includes base limits. Additional usage will be charged at the end of your billing cycle:
                </p>
                <ul class="text-accent text-sm space-y-1">
                    <li>• Extra students: ₹5 per student per month</li>
                    <li>• Additional storage: ₹50 per GB per month</li>
                    <li>• Extra tenants: ₹500 per tenant per month</li>
                </ul>
            </div>
        </div>
    </div>
</section>
