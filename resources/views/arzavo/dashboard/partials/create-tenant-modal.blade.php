<!-- Create Tenant Modal -->
<div id="createTenantModal" class="fixed inset-0 bg-invert bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-primary border-primary border-rounded max-w-2xl w-full max-h-screen overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-tertiary">
                <h3 class="text-xl font-bold text-primary">Create New Tenant</h3>
                <button onclick="closeCreateTenantModal()" class="text-tertiary hover:text-primary">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold text-primary mb-4">Basic Information</h4>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-secondary font-medium mb-2">Institution Name *</label>
                                <input type="text" id="name" name="name" required 
                                       class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                       placeholder="e.g., Delhi Public School">
                            </div>
                            
                            <div>
                                <label for="subdomain" class="block text-secondary font-medium mb-2">Subdomain *</label>
                                <div class="flex">
                                    <input type="text" id="subdomain" name="subdomain" required 
                                           class="flex-1 px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                           placeholder="school-name">
                                    <span class="px-4 py-3 bg-tertiary text-secondary border-primary border-l-0 border-rounded-r text-sm">
                                        .arzavo.in
                                    </span>
                                </div>
                                <p class="text-tertiary text-sm mt-1">Choose a unique subdomain for your platform</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h4 class="text-lg font-semibold text-primary mb-4">Contact Information</h4>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-secondary font-medium mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                       placeholder="admin@school.com">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-secondary font-medium mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                       placeholder="+91 98765 43210">
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div>
                        <h4 class="text-lg font-semibold text-primary mb-4">Address Information</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-secondary font-medium mb-2">Address</label>
                                <textarea id="address" name="address" rows="3" 
                                          class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary resize-none"
                                          placeholder="Street address, building number, etc."></textarea>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-secondary font-medium mb-2">City</label>
                                    <input type="text" id="city" name="city" 
                                           class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                           placeholder="Mumbai">
                                </div>
                                
                                <div>
                                    <label for="state" class="block text-secondary font-medium mb-2">State</label>
                                    <input type="text" id="state" name="state" 
                                           class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                           placeholder="Maharashtra">
                                </div>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="country" class="block text-secondary font-medium mb-2">Country</label>
                                    <select id="country" name="country" 
                                            class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary">
                                        <option value="India">India</option>
                                        <option value="USA">United States</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="pincode" class="block text-secondary font-medium mb-2">Pincode</label>
                                    <input type="text" id="pincode" name="pincode" 
                                           class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                           placeholder="400001">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Institution Details -->
                    <div>
                        <h4 class="text-lg font-semibold text-primary mb-4">Institution Details</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="about" class="block text-secondary font-medium mb-2">About Institution</label>
                                <textarea id="about" name="about" rows="4" 
                                          class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary resize-none"
                                          placeholder="Brief description about your educational institution..."></textarea>
                            </div>
                            
                            <div>
                                <label for="heading" class="block text-secondary font-medium mb-2">Institution Tagline</label>
                                <input type="text" id="heading" name="heading" 
                                       class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                       placeholder="Excellence in Education">
                            </div>
                        </div>
                    </div>

                    <!-- Branding -->
                    <div>
                        <h4 class="text-lg font-semibold text-primary mb-4">Branding (Optional)</h4>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="logo" class="block text-secondary font-medium mb-2">Logo</label>
                                <input type="file" id="logo" name="logo" accept="image/*" 
                                       class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary">
                                <p class="text-tertiary text-sm mt-1">Recommended: 200x200px, PNG or JPG</p>
                            </div>
                            
                            <div>
                                <label for="banner" class="block text-secondary font-medium mb-2">Banner Image</label>
                                <input type="file" id="banner" name="banner" accept="image/*" 
                                       class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary">
                                <p class="text-tertiary text-sm mt-1">Recommended: 1200x400px, PNG or JPG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Domain -->
                    <div>
                        <h4 class="text-lg font-semibold text-primary mb-4">Custom Domain (Optional)</h4>
                        
                        <div>
                            <label for="custom_domain" class="block text-secondary font-medium mb-2">Custom Domain</label>
                            <input type="text" id="custom_domain" name="custom_domain" 
                                   class="w-full px-4 py-3 bg-secondary border-primary border-rounded input-focus text-primary"
                                   placeholder="school.example.com">
                            <p class="text-tertiary text-sm mt-1">You can add this later from your dashboard</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between pt-6 border-t border-tertiary mt-8">
                    <button type="button" onclick="closeCreateTenantModal()" 
                            class="bg-secondary text-primary px-6 py-3 border-rounded font-medium hover-primary transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-accent text-invert px-8 py-3 border-rounded font-semibold hover-invert transition-all">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Real-time subdomain validation
document.getElementById('subdomain').addEventListener('input', function() {
    const subdomain = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
    this.value = subdomain;
    
    // Check availability (you can implement AJAX check here)
    if (subdomain.length >= 3) {
        // Show availability status
        console.log('Checking availability for:', subdomain);
    }
});

// Auto-generate subdomain from institution name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value.toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '-')
        .substring(0, 20);
    
    const subdomainField = document.getElementById('subdomain');
    if (!subdomainField.value) {
        subdomainField.value = name;
    }
});
</script>