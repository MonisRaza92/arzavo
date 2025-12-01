<!-- 🔐 Connect Domain Popup -->
<div id="connectDomainPopup-{{ $tenant->id }}"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    
    <div class="bg-white border-rounded w-full max-w-lg max-h-[90vh] overflow-auto scrollbar p-6 relative animate-fadeIn">

        <!-- Close Button -->
        <button id="closeDomainPopup"
            class="absolute right-3 top-3 text-gray-500 hover:text-black text-2xl">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Title -->
        <h2 class="text-xl font-bold text-primary mb-2 flex items-center gap-2">
            <i class="fa-solid fa-globe"></i> Connect Your Domain
        </h2>

        <!-- Subtitle -->
        <p class="text-sm text-gray-600 leading-relaxed mb-4">
            Connect your own domain (like <b>school.com</b>) and make your website accessible on your personal domain.  
            Follow the steps below to complete the setup.
        </p>

        <!-- Domain Input -->
        <div class="hidden">
            <label class="block text-xs font-semibold text-secondary mb-1">Your Domain Name</label>
            <input 
                type="text" 
                id="newCustomDomain"
                class="w-full border-primary border-rounded p-2 text-tertiary"
                placeholder="e.g. school.com"
                readonly
                value="{{ $tenant->custom_domain }}"
            >
        </div>

        <!-- Step-by-step Guide -->
        <div class="bg-gray-100 p-4 rounded-md text-sm text-gray-700 mb-4 leading-relaxed">

            <h3 class="text-sm font-bold text-primary mb-2">Steps to Connect Your Domain</h3>

            <ol class="list-decimal pl-4 space-y-3">

                <li>
                    <strong>Log in to your Domain Provider</strong>
                    <ul class="list-disc pl-5 mt-1 space-y-1">
                        <li>GoDaddy</li>
                        <li>Namecheap</li>
                        <li>Hostinger</li>
                        <li>Cloudflare</li>
                        <li>or whichever service you use.</li>
                    </ul>
                </li>

                <li>
                    <strong>Open DNS Management / Zone Editor</strong><br>
                    This is where you add and manage DNS records for your domain.
                </li>

                <li>
                    <strong>Add the following 3 DNS Records:</strong>
                    <div class="bg-white p-3 rounded border mt-2 space-y-3">

                        <div>
                            <strong>Record 1 — Main Domain (A Record):</strong><br>
                            <code class="text-xs">Type: A | Name: @ | Value: 3.80.86.193 | TTL: 3600</code>
                        </div>

                        <div>
                            <strong>Record 2 — Verification (CNAME):</strong><br>
                            <code class="text-xs">Type: CNAME | Name: verify | Value: verify.{{ config('app.domain') }} | TTL: 3600</code>
                        </div>

                        <div>
                            <strong>Record 3 — WWW Domain (A Record):</strong><br>
                            <code class="text-xs">Type: A | Name: www | Value: 3.80.86.193 | TTL: 3600</code>
                        </div>

                    </div>
                </li>

                <li>
                    <strong>Wait for DNS propagation</strong><br>
                    It usually takes 5–30 minutes but may take up to 24 hours.
                </li>

                <li>
                    <strong>Click the "Verify Domain" button below</strong>  
                    to confirm your domain connection.
                </li>

            </ol>
        </div>

        <!-- Domain Display -->
        <div class="bg-gray-50 border border-primary rounded-md p-3 mb-4">
            <p class="text-sm text-gray-700">
                <strong>Domain to verify:</strong>
                <span id="domainDisplay" class="text-primary">{{ $tenant->custom_domain ?? 'None' }}</span>
            </p>
        </div>

        <!-- Verify Button -->
        <button id="verifyNewDomainBtn"
            class="w-full bg-invert text-invert font-semibold py-3 border-rounded transition text-center">
            Verify Domain
        </button>

        <!-- Status Message -->
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

    if (!openBtn) return;

    openBtn.addEventListener("click", () => {
        popup.classList.remove("hidden");
        statusMsg.textContent = "";
    });

    closeBtn.addEventListener("click", () => popup.classList.add("hidden"));

    verifyBtn.addEventListener("click", function() {
        const domain = domainInput.value.trim();
        if (!domain) {
            alert("⚠️ Please enter a domain first.");
            return;
        }

        statusMsg.textContent = "⏳ Verifying domain...";
        statusMsg.className = "text-center text-sm text-gray-500";

        fetch(`{{ route('domain.verify', $tenant->id) }}?domain=${domain}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    statusMsg.textContent = data.message;
                    statusMsg.className = "text-center text-sm text-green-600";

                    setTimeout(() => {
                        popup.classList.add("hidden");
                        window.location.reload();
                    }, 1500);
                } else {
                    statusMsg.textContent = data.message;
                    statusMsg.className = "text-center text-sm text-red-600";
                }
            })
            .catch(() => {
                statusMsg.textContent = "❌ Something went wrong. Please retry.";
                statusMsg.className = "text-center text-sm text-red-600";
            });
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to   { opacity: 1; transform: scale(1); }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}
</style>
