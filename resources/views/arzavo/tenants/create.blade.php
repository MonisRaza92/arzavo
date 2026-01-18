@if($user->tenants()->count() < 2)
    <div class="tenant-form border-rounded p-3 mt-4 border-primary w-full">
    <h2 class="text-xl font-bold text-primary mb-4 flex items-center gap-2">
        <i class="fa-solid fa-building-columns text-base"></i> Create New Tenant
    </h2>

    <form action="{{ route('tenants.store') }}" method="POST">
        @csrf
        <!-- Tenant Name -->
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="mb-4">
            <label class="block text-xs font-semibold text-secondary mb-1">Tenant Name</label>
            <input type="text" name="name" required
                value="{{ old('name') }}"
                class="w-full border-primary border-rounded p-2 text-tertiary"
                placeholder="e.g. Arzavo Academy, Arzaq School">
            <p class="text-[11px] text-tertiary mt-1">This is your organization’s official display name.</p>
        </div>

        <!-- domain -->
        <div class="mb-4 relative">
            <label class="block text-xs font-semibold text-secondary mb-1">Tenant Domain</label>
            <input type="text" name="subdomain" required
                value="{{ old('subdomain') }}"
                class="w-full border-primary border-rounded p-2 text-tertiary"
                placeholder="e.g. tenant">
            <span class="absolute right-0 border-left text-tertiary p-2 top-5">{{ config('app.domain') }}</span>

            <div class="mt-1">
                <p class="text-[11px] text-tertiary">Your tenant will be available at <b class="text-primary">{{ old('domain') ? old('domain') : 'tenant' }}.{{ config('app.domain') }}</b></p>
                <p class="text-[11px] text-tertiary"><i class="fa-regular fa-check-circle text-green-500"></i> Recommended for most users<br><i class="fa-regular fa-check-circle text-green-500"></i> No DNS configuration needed
                </p>
            </div>
        </div>

        <!-- Custom Domain (Optional with Verify button) -->
        <div class="mb-6 relative">
            <label class="block text-xs font-semibold text-secondary mb-1">Custom Domain (Optional)</label>

            <input type="text" id="domain" name="custom_domain"
                value="{{ old('custom_domain') }}"
                class="w-full border-primary border-rounded p-2 text-tertiary pr-24"
                placeholder="e.g. schooldomain.com">

            <ul class="text-[11px] text-tertiary mt-1 list-disc pl-4">
                <li>Requires domain ownership</li>
                <li>You must add DNS CNAME record pointing to <span class="font-semibold">{{ config('app.domain') }}</span></li>
                <li>SSL certificate will be auto-generated after verification</li>
            </ul>
        </div>


        <!-- Create Button -->
        <button type="submit"
            class="w-full py-2.5 bg-invert text-invert font-bold border-rounded transition">
            Create Tenant
        </button>
    </form>
    <div class="mt-5 bg-accent-subtle border-primary border-rounded p-3 text-[12px] text-accent" style="border-color: var(--bg-accent);">
        <b>Need Help?</b><br>
        You can start with a domain and later switch to a custom domain anytime.
    </div>
    </div>
    @endif
