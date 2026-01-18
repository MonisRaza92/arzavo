<!-- Tenants Section -->
<section id="tenants" class="space-y-6">
    <!-- Section Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-primary">My Tenants</h2>
            <p class="text-secondary">Manage all your educational platforms in one place</p>
        </div>
        <button onclick="openCreateTenantModal()" class="bg-accent text-invert px-6 py-3 border-rounded font-semibold hover-invert transition-all">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Tenant
        </button>
    </div>

    <!-- Tenants Grid -->
    @if($tenants->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tenants as $tenant)
        <div class="bg-primary border-primary border-rounded p-6 hover:shadow-lg transition-all duration-300">
            <!-- Tenant Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    @if($tenant->logo)
                        <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-12 h-12 border-rounded object-cover">
                    @else
                        <div class="w-12 h-12 bg-accent border-rounded flex items-center justify-center">
                            <i class="fa-solid fa-building text-invert text-xl"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-bold text-primary">{{ $tenant->name }}</h3>
                        <p class="text-tertiary text-sm">{{ Str::limit($tenant->about, 30) }}</p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                <span class="px-3 py-1 border-rounded text-xs font-semibold
                    {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>

            <!-- Tenant Info -->
            <div class="space-y-3 mb-6">
                <!-- Subdomain -->
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-globe text-tertiary w-4"></i>
                    <div class="flex-1">
                        <p class="text-tertiary text-xs">Subdomain</p>
                        <a href="https://{{ $tenant->subdomain }}" target="_blank" 
                           class="text-accent text-sm font-medium hover:underline">
                            {{ $tenant->subdomain }}
                        </a>
                    </div>
                </div>

                <!-- Custom Domain -->
                @if($tenant->custom_domain)
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-link text-tertiary w-4"></i>
                    <div class="flex-1">
                        <p class="text-tertiary text-xs">Custom Domain</p>
                        <div class="flex items-center space-x-2">
                            <a href="https://{{ $tenant->custom_domain }}" target="_blank" 
                               class="text-accent text-sm font-medium hover:underline">
                                {{ $tenant->custom_domain }}
                            </a>
                            @if($tenant->domain_verified)
                                <span class="text-green-600 text-xs">
                                    <i class="fa-solid fa-check-circle"></i> Verified
                                </span>
                            @else
                                <span class="text-yellow-600 text-xs">
                                    <i class="fa-solid fa-clock"></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-tertiary">
                    <div class="text-center">
                        <div class="text-lg font-bold text-accent">{{ rand(50, 500) }}</div>
                        <div class="text-tertiary text-xs">Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-accent">{{ rand(5, 25) }}</div>
                        <div class="text-tertiary text-xs">Courses</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <a href="https://{{ $tenant->custom_domain && $tenant->domain_verified ? $tenant->custom_domain : $tenant->subdomain }}/admin/dashboard" 
                   target="_blank"
                   class="flex-1 bg-accent text-invert px-4 py-2 border-rounded text-center text-sm font-medium hover-invert transition-all">
                    <i class="fa-solid fa-external-link-alt mr-2"></i>
                    Open Dashboard
                </a>
                
                <div class="flex space-x-1">
                    <button class="bg-secondary text-primary w-10 h-10 border-rounded hover-primary transition-all">
                        <i class="fa-solid fa-edit text-sm"></i>
                    </button>
                    
                    <button onclick="toggleTenantStatus({{ $tenant->id }})" 
                            class="bg-secondary text-primary w-10 h-10 border-rounded hover-primary transition-all">
                        <i class="fa-solid fa-power-off text-sm"></i>
                    </button>
                    
                    <button onclick="deleteTenant({{ $tenant->id }})" 
                            class="bg-red-100 text-red-600 w-10 h-10 border-rounded hover:bg-red-200 transition-all">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Created Date -->
            <div class="mt-4 pt-3 border-t border-tertiary">
                <p class="text-tertiary text-xs">
                    <i class="fa-solid fa-calendar mr-1"></i>
                    Created {{ $tenant->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-primary border-primary border-rounded p-12 text-center">
        <div class="w-24 h-24 bg-secondary border-rounded flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-building text-4xl text-tertiary"></i>
        </div>
        <h3 class="text-xl font-bold text-primary mb-4">No Tenants Yet</h3>
        <p class="text-secondary mb-8 max-w-md mx-auto">
            Get started by creating your first educational platform. It only takes a few minutes to set up!
        </p>
        <button onclick="openCreateTenantModal()" class="bg-accent text-invert px-8 py-4 border-rounded font-semibold hover-invert transition-all">
            <i class="fa-solid fa-plus mr-2"></i>
            Create Your First Tenant
        </button>
    </div>
    @endif

    <!-- Tenant Limits Info -->
    <div class="bg-accent-subtle border-accent border-rounded p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-accent text-invert w-12 h-12 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-info"></i>
                </div>
                <div>
                    <h4 class="text-accent font-semibold">Tenant Limit</h4>
                    <p class="text-accent text-sm">
                        You can create {{ $tenants->count() }}/1 tenants with your current plan.
                        @if($tenants->count() >= 1)
                            Upgrade to create more tenants.
                        @endif
                    </p>
                </div>
            </div>
            @if($tenants->count() >= 1)
            <a href="#subscriptions" class="bg-accent text-invert px-6 py-3 border-rounded font-semibold hover-invert transition-all">
                Upgrade Plan
            </a>
            @endif
        </div>
    </div>
</section>

<script>
function toggleTenantStatus(tenantId) {
    if (confirm('Are you sure you want to change the status of this tenant?')) {
        fetch(`/tenant/toggle-status/${tenantId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating tenant status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating tenant status');
        });
    }
}

function deleteTenant(tenantId) {
    if (confirm('Are you sure you want to delete this tenant? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenants/${tenantId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
