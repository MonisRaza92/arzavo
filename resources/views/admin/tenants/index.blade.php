@extends('layouts.app')
@section('title', 'Create Tenants - ' . config('app.name'))

@section('content')
<div class="flex items-start justify-start relative h-dvh w-full">
    <img src="{{ asset('images/banner/tenant-banner.webp') }}" alt="Arzavo Logo" class="absolute top-0 inset-0 left-0 w-full h-dvh object-cover">
    <div class="border-right bg-primary z-10 h-full w-full md:w-96 overflow-auto pb-4 scrollbar">
        <div class="logo-container sticky top-0 bg-primary flex justify-between items-center border-bottom p-4 mb-4">
            <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo" class="logo">
            <a href="#" class="font-bold bg-invert text-invert border-rounded px-4 py-2">Upgrade</a>
        </div>
        <div class="content px-4 w-full">
            <!-- Notification Badge -->
            <div id="tenant-notification" class="w-full border-primary bg-hover-primary cursor-default border-rounded p-2 flex justify-between items-center text-sm transition-all duration-200">
                <div class="text flex gap-2 items-center">
                    <i class="fa-regular fa-check-circle text-2xl text-green-500"></i>
                    <p class="text-xs text-tertiary">You can create only one tenant.<br> Please upgrade your plan to add more tenants.</p>
                </div>
                <button onclick="document.getElementById('tenant-notification').classList.add('hidden')" class="text-tertiary text-lg"> <i class="fa-solid fa-xmark"></i> </button>
            </div>
            <!-- Tenant List -->
            @if($tenants->count() > 0)
            @foreach($tenants as $tenant)
            <div class="tenant-item border-primary border-rounded mt-4 w-full">

                <!-- Header -->
                <div class="flex justify-between items-center p-3 border-bottom">
                    <h2 class="text-lg font-bold text--primary flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-base"></i>
                        {{ $tenant->name }}
                    </h2>

                    <!-- Status Toggle Button -->
                    <button onclick="toggleTenantStatus({{ $tenant->id }})"
                        class="text-[10px] font-semibold px-2 py-1 rounded-full
                        {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </div>

                <!-- domain -->
                <div class="flex items-center text-sm py-1 border-bottom">
                    <div class="p-3 border-right mr-3">
                        <i class="fa-solid fa-globe text-tertiary"></i>
                    </div>
                    <div>
                        <strong class="text-secondary text-xs leading-none">Subdomain:</strong><br>
                        <a target="_blank"
                            href="https://{{$tenant->subdomain}}"
                            class="text-xs font-medium text-indigo-600 leading-none hover:underline">
                            {{ $tenant->subdomain }}
                        </a>
                    </div>
                </div>

                <!-- Custom Domain -->
                <div class="flex items-center text-sm py-1 border-bottom">
                    <div class="p-3 border-right mr-3">
                        <i class="fa-solid fa-link text-tertiary"></i>
                    </div>
                    <div>
                        <strong class="text-secondary text-xs leading-none">Custom Domain:</strong><br>

                        @if($tenant->domain)
                        <a target="_blank"
                            href="https://{{$tenant->domain}}"
                            class="text-xs font-medium text-indigo-600 leading-none hover:underline">
                            {{ $tenant->domain }}
                        </a>
                        <span class="text-[10px] text-green-600 font-semibold ml-1">✔ Verified</span>
                        @else
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-red-600 font-medium">Not Connected</span>
                            <div class="text-xs text-gray-500">
                                <button type="button" id="connectDomainBtn-{{ $tenant->id }}"
                                    class="text-indigo-600 hover:underline">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- 🔐 Connect Domain Popup -->
                        <div id="connectDomainPopup-{{ $tenant->id }}"
                            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white border-rounded w-[90%] h-[90%] overflow-auto scrollbar max-w-md p-6 relative animate-fadeIn">

                                <button id="closeDomainPopup" class="absolute right-3 top-3 text-gray-500 hover:text-black text-xl"><i class="fa-solid fa-xmark"></i></button>

                                <h2 class="text-xl font-bold text-primary mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-globe"></i> Connect Your Domain
                                </h2>

                                <p class="text-sm text-gray-600 mb-4">
                                    Add your own domain (like <b>school.com</b>) to make your tenant accessible directly.
                                </p>

                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-secondary mb-1">Your Domain Name</label>
                                    <input type="text" id="newCustomDomain"
                                        class="w-full border-primary border-rounded p-2 text-tertiary"
                                        placeholder="e.g. school.com">
                                </div>

                                <div class="bg-gray-100 p-3 rounded-md text-sm text-gray-700 mb-4">
                                    <ol class="list-decimal pl-4 space-y-2">
                                        <li><strong>Access your Domain DNS Panel:</strong>
                                            <ul class="list-disc pl-5 mt-1">
                                                <li>Login to your domain provider (GoDaddy, Namecheap, Cloudflare, etc.)</li>
                                                <li>Navigate to DNS Management / Zone Editor</li>
                                                <li>Find the option to add new DNS records</li>
                                            </ul>
                                        </li>
                                        <li><strong>Add these 3 DNS records:</strong>
                                            <div class="bg-white p-3 rounded border mt-2 space-y-2">
                                                <div>
                                                    <strong>Record 1 - Main Domain (A Record):</strong><br>
                                                    <code>Type: A | Name: @ | Value: 82.180.143.53 | TTL: 3600</code>
                                                </div>
                                                <div>
                                                    <strong>Record 2 - Verification (CNAME Record):</strong><br>
                                                    <code>Type: CNAME | Name: verify | Value: verify.{{config('app.domain')}} | TTL: 3600</code>
                                                </div>
                                                <div>
                                                    <strong>Record 3 - WWW Domain (A Record):</strong><br>
                                                    <code>Type: A | Name: www | Value: 82.180.143.53 | TTL: 3600</code>
                                                </div>
                                            </div>
                                        </li>
                                        <li><strong>Wait for DNS propagation (30 minutes - 24 hours)</strong></li>
                                        <li><strong>Click "Verify Domain" below to confirm connection</strong></li>
                                    </ol>
                                </div>

                                <div class="bg-gray-50 border border-primary rounded-md p-3 mb-4">
                                    <p class="text-sm text-gray-700">
                                        <strong>Domain to verify:</strong>
                                        <span id="domainDisplay" class="text-primary">None</span>
                                    </p>
                                </div>

                                <button id="verifyNewDomainBtn"
                                    class="w-full bg-invert text-invert font-semibold py-3 border-rounded transition">
                                    Verify Domain
                                </button>

                                <p id="domainVerifyStatus" class="text-center text-sm mt-3"></p>
                            </div>
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const openBtn = document.getElementById("connectDomainBtn-{{ $tenant->id }}");
                                const popup = document.getElementById("connectDomainPopup-{{ $tenant->id }}");
                                const closeBtn = document.getElementById("closeDomainPopup");
                                const domainInput = document.getElementById("newCustomDomain");
                                const verifyBtn = document.getElementById("verifyNewDomainBtn");
                                const domainDisplay = document.getElementById("domainDisplay");
                                const statusMsg = document.getElementById("domainVerifyStatus");

                                if (!openBtn) return; // For safety if tenant already verified

                                openBtn.addEventListener("click", () => {
                                    popup.classList.remove("hidden");
                                    statusMsg.textContent = "";
                                    domainDisplay.textContent = "None";
                                });

                                closeBtn.addEventListener("click", () => {
                                    popup.classList.add("hidden");
                                });

                                // Update domain display dynamically
                                domainInput.addEventListener("input", () => {
                                    domainDisplay.textContent = domainInput.value.trim() || "None";
                                });

                                verifyBtn.addEventListener("click", function() {
                                    const domain = domainInput.value.trim();
                                    if (!domain) {
                                        alert("⚠️ Please enter a domain first.");
                                        return;
                                    }

                                    statusMsg.textContent = "⏳ Verifying domain...";
                                    statusMsg.classList.add("text-gray-500");

                                    fetch(`{{ route('admin.domain.connect', $tenant->id) }}?domain=${domain}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.status === 'success') {
                                                statusMsg.textContent = data.message;
                                                statusMsg.classList.remove("text-gray-500");
                                                statusMsg.classList.add("text-green-600");

                                                setTimeout(() => {
                                                    popup.classList.add("hidden");
                                                    window.location.reload();
                                                }, 1500);
                                            } else {
                                                statusMsg.textContent = data.message;
                                                statusMsg.classList.remove("text-gray-500");
                                                statusMsg.classList.add("text-red-600");
                                            }
                                        })
                                        .catch(() => {
                                            statusMsg.textContent = "❌ Something went wrong. Please retry.";
                                            statusMsg.classList.add("text-red-600");
                                        });
                                });
                            });
                        </script>

                        <style>
                            @keyframes fadeIn {
                                from {
                                    opacity: 0;
                                    transform: scale(0.95);
                                }

                                to {
                                    opacity: 1;
                                    transform: scale(1);
                                }
                            }

                            .animate-fadeIn {
                                animation: fadeIn 0.3s ease-in-out;
                            }
                        </style>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center p-1">
                    <div class="flex">

                        <a href="#" class="text-sm text-tertiary p-2 pr-3 border-right"><i class="fa-solid fa-pen-to-square"></i></a>

                        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm py-2 px-3 text-tertiary border-right" onclick="return confirm('Are you sure you want to delete this tenant?');">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </div>
                    @if($tenant->domain)
                    <a target="_blank" href="http://{{$tenant->domain}}/admin/dashboard"
                        class="text-sm text-indigo-600 hover:underline p-2 border-left font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        View Dashboard
                    </a>
                    @else
                    <a target="_blank" href="http://{{$tenant->subdomain}}/admin/dashboard"
                        class="text-sm text-indigo-600 hover:underline p-2 border-left font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        View Dashboard
                    </a>
                    @endif
                </div>

            </div>
            @endforeach
            @else
            <p class="text-gray-500 text-sm mt-3">No tenants found.</p>
            @endif
            <!-- Tenant Creation Form -->
            @if(Auth::user()->tenants()->count() < 1)
                <div class="tenant-form border-rounded p-3 mt-4 border-primary w-full">
                <h2 class="text-xl font-bold text-primary mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-building-columns text-base"></i> Create New Tenant
                </h2>

                <form action="{{ route('admin.tenants.store') }}" method="POST">
                    @csrf
                    <!-- Tenant Name -->
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-secondary mb-1">Tenant Name</label>
                        <input type="text" name="name" required
                            value="{{ old('name') }}"
                            class="w-full border-primary border-rounded p-2 text-tertiary"
                            placeholder="e.g. Arzavo Academy, Arzaq School">
                        <p class="text-[11px] text-tertiary mt-1">This is your organization’s official display name.</p>
                    </div>

                    <!-- domain -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-secondary mb-1">Tenant domain</label>
                        <input type="text" name="subdomain" required
                            value="{{ old('subdomain') }}"
                            class="w-full border-primary border-rounded p-2 text-tertiary"
                            placeholder="e.g. arzavo">

                        <div class="mt-1">
                            <p class="text-[11px] text-tertiary">Enter only tenant username don't type (arzavo.test) <b class="text-primary">{{ old('domain') ? old('domain') : 'domain' }}.{{ config('app.domain') }}</b></p>
                            <p class="text-[11px] text-tertiary">Your tenant will be available at <b class="text-primary">{{ old('domain') ? old('domain') : 'domain' }}.{{ config('app.domain') }}</b></p>
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

                        <button type="button" id="verifyBtn"
                            class="absolute right-1 top-6 mt-px text-xs bg-accent text-invert font-semibold py-2 px-4 border-rounded transition">
                            Verify
                        </button>

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

                <!-- ✅ Popup (Hidden by default) -->
                <div id="verifyPopup"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-primary border-rounded w-[90%] h-[90%] overflow-auto scrollbar max-w-md p-6 relative animate-fadeIn">

                        <button id="closePopup" class="absolute right-3 top-3 text-gray-500 hover:text-black text-xl"><i class="fa-solid fa-xmark"></i></button>

                        <h2 class="text-xl font-bold text-primary mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-globe"></i> Domain Verification Guide
                        </h2>

                        <p class="text-sm text-gray-600 mb-4">
                            To verify your custom domain, please follow these simple steps. Make sure you own the domain
                            and can manage its DNS settings.
                        </p>

                        <div class="bg-gray-100 p-3 border-rounded text-sm text-gray-700 mb-4">
                            <ol class="list-decimal pl-4 space-y-2">
                                <li><strong>Access your Domain DNS Panel:</strong>
                                    <ul class="list-disc pl-5 mt-1">
                                        <li>Login to your domain provider (GoDaddy, Namecheap, Cloudflare, etc.)</li>
                                        <li>Navigate to DNS Management / Zone Editor</li>
                                        <li>Find the option to add new DNS records</li>
                                    </ul>
                                </li>
                                <li><strong>Add these 3 DNS records:</strong>
                                    <div class="bg-white p-3 rounded border mt-2 space-y-2">
                                        <div>
                                            <strong>Record 1 - Main Domain (A Record):</strong><br>
                                            <code>Type: A | Name: @ | Value: 82.180.143.53 | TTL: 3600</code>
                                        </div>
                                        <div>
                                            <strong>Record 2 - Verification (CNAME Record):</strong><br>
                                            <code>Type: CNAME | Name: verify | Value: verify.{{config('app.domain')}} | TTL: 3600</code>
                                        </div>
                                        <div>
                                            <strong>Record 3 - WWW Domain (A Record):</strong><br>
                                            <code>Type: A | Name: www | Value: 82.180.143.53 | TTL: 3600</code>
                                        </div>
                                    </div>
                                </li>
                                <li><strong>Wait for DNS propagation (30 minutes - 24 hours)</strong></li>
                                <li><strong>Click "Verify Domain" below to confirm connection</strong></li>
                            </ol>
                        </div>

                        <div class="bg-gray-50 border border-primary rounded-md p-3 mb-4">
                            <p class="text-sm text-gray-700">
                                <strong>Domain to verify:</strong>
                                <span id="domainToVerify" class="text-primary"></span>
                            </p>
                        </div>

                        <button id="popupVerifyBtn"
                            class="w-full bg-invert text-invert font-semibold py-3 border-rounded transition">
                            Verify Domain
                        </button>

                        <p id="verifyStatus" class="text-center text-sm mt-3"></p>
                    </div>
                </div>

                <!-- JS Section -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const verifyBtn = document.getElementById("verifyBtn");
                        const popup = document.getElementById("verifyPopup");
                        const closePopup = document.getElementById("closePopup");
                        const popupVerifyBtn = document.getElementById("popupVerifyBtn");
                        const domainToVerify = document.getElementById("domainToVerify");
                        const verifyStatus = document.getElementById("verifyStatus");
                        const customDomainInput = document.getElementById("domain");
                        const form = document.querySelector("form");

                        let isVerified = false;

                        // Open Popup
                        verifyBtn.addEventListener("click", function() {
                            const domain = customDomainInput.value.trim();

                            if (!domain) {
                                alert("⚠️ Please enter a domain before verifying.");
                                return;
                            }

                            domainToVerify.textContent = domain;
                            verifyStatus.textContent = "";
                            popup.classList.remove("hidden");
                        });

                        // Close Popup
                        closePopup.addEventListener("click", () => {
                            popup.classList.add("hidden");
                        });

                        // Verify Domain (AJAX) - Updated to pass domain as parameter
                        popupVerifyBtn.addEventListener("click", function() {
                            const domain = customDomainInput.value.trim();

                            if (!domain) {
                                alert("⚠️ Please enter a domain before verifying.");
                                return;
                            }

                            verifyStatus.textContent = "⏳ Verifying domain...";
                            verifyStatus.classList.add("text-gray-500");

                            // Create form data to send the domain
                            const formData = new FormData();
                            formData.append('domain', domain);
                            formData.append('_token', '{{ csrf_token() }}');

                            fetch('{{ route("admin.domain.verify") }}', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.message.includes("✅")) {
                                        verifyStatus.textContent = data.message;
                                        verifyStatus.classList.remove("text-gray-500");
                                        verifyStatus.classList.add("text-green-600");

                                        setTimeout(() => {
                                            popup.classList.add("hidden");
                                            customDomainInput.disabled = true;
                                            verifyBtn.textContent = "✅ Verified";
                                            verifyBtn.classList.remove("bg-accent");
                                            verifyBtn.classList.add("bg-green-500", "cursor-default");
                                            isVerified = true;
                                        }, 1500);
                                    } else {
                                        verifyStatus.textContent = data.message;
                                        verifyStatus.classList.remove("text-gray-500");
                                        verifyStatus.classList.add("text-red-600");
                                    }
                                })
                                .catch(() => {
                                    verifyStatus.textContent = "❌ Verification failed. Please try again.";
                                    verifyStatus.classList.add("text-red-600");
                                });
                        });

                        // Block Form Submission if domain entered but not verified
                        form.addEventListener("submit", function(e) {
                            const domain = customDomainInput.value.trim();
                            if (domain && !isVerified) {
                                e.preventDefault();
                                alert("⚠️ Please verify your custom domain before submitting.");
                            }
                        });
                    });
                </script>

                <style>
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: scale(0.95);
                        }

                        to {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }

                    .animate-fadeIn {
                        animation: fadeIn 0.3s ease-in-out;
                    }
                </style>
                <div class="mt-5 bg-accent-subtle border-primary border-rounded p-3 text-[12px] text-accent" style="border-color: var(--bg-accent);">
                    <b>Need Help?</b><br>
                    You can start with a domain and later switch to a custom domain anytime.
                </div>
        </div>
        @endif
    </div>
</div>
</div>

@endsection