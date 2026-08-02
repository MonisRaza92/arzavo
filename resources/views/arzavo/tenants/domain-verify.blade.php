<!-- 🔐 Connect Domain Popup -->
<div id="connectDomainPopup-{{ $tenant->id }}"
    class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[999]">

    <div class="bg-primary border-rounded w-full max-w-4xl p-6 md:h-auto h-dvh relative overflow-auto scrollbar animate-fadeIn shadow-xl border-primary">

        <!-- Close Button -->
        <button id="closeDomainPopup-{{ $tenant->id }}"
            class="absolute right-4 top-6 text-secondary hover:text-primary text-2xl transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- HEADER -->
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 flex items-center justify-center bg-invert text-invert border-rounded text-2xl">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-primary">Connect Your Domain</h2>
                <p class="text-sm text-secondary">Make your website live on your own custom domain.</p>
            </div>
        </div>

        <!-- Steps Box -->
        <div class="bg9-secondary border-primary border-rounded p-5 mb-6">

            <h3 class="text-primary font-semibold text-sm mb-3">How to connect your domain</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Step 1 -->
                <div class="bg-primary border-primary border-rounded p-4">
                    <p class="font-bold text-primary text-sm mb-2">1. Open your domain provider</p>

                    <ul class="text-xs text-secondary list-disc pl-4 space-y-1 mb-3">
                        <li>GoDaddy</li>
                        <li>Namecheap</li>
                        <li>Hostinger</li>
                        <li>or whichever provider you use</li>
                    </ul>

                    <div class="text-xs text-secondary space-y-1">
                        <p class="font-semibold text-primary">Inside your provider panel:</p>

                        <p>• Go to <b>DNS Management</b> or <b>DNS Zone Editor</b></p>
                        <p>• Sometimes it appears under:
                            <span class="text-primary">
                                <b>Domain Settings → Manage DNS</b>
                            </span>
                        </p>

                        <p>• Look for buttons like:
                            <span class="text-primary">
                                <b>Add Record</b> / <b>Create New DNS Record</b>
                            </span>
                        </p>
                    </div>
                </div>


                <!-- Step 2 -->
                <div class="bg-primary border-primary border-rounded p-4">
                    <p class="font-bold text-primary text-sm mb-1">2. Add these DNS Records</p>

                    <div class="space-y-2">

                        <div class="bg-hover-secondary border-primary border-rounded p-2 text-xs">
                            <strong class="text-primary">A Record:</strong><br>
                            <span class="text-secondary">@ → <code class="font-mono text-primary">{{ env('SERVER_IP', '13.234.98.38') }}</code></span>
                        </div>

                        <div class="bg-hover-secondary border-primary border-rounded p-2 text-xs">
                            <strong class="text-primary">CNAME:</strong><br>
                            <span class="text-secondary">verify → <code class="font-mono text-primary">verify.{{ config('app.domain') }}</code></span>
                        </div>

                        <div class="bg-hover-secondary border-primary border-rounded p-2 text-xs">
                            <strong class="text-primary">A Record (www):</strong><br>
                            <span class="text-secondary">www → <code class="font-mono text-primary">{{ env('SERVER_IP', '13.234.98.38') }}</code></span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Domain Input -->
        <span class="text-sm text-primary font-semibold">Your Domain to verify</span>
        <input
            type="text"
            id="newCustomDomain-{{ $tenant->id }}"
            value="{{ $tenant->custom_domain }}"
            class="w-full text-primary mt-2 border-rounded p-3 mb-4 border-primary bg-primary"
            placeholder="e.g. academy.yourdomain.com"
            />
        <input type="hidden" value="{{ $tenant->id }}" name="id">

        <!-- Status Box -->
        <div id="statusBox-{{ $tenant->id }}" class="hidden p-3 border-rounded mb-4 text-sm"></div>

        <!-- Verify Button -->
        <button id="verifyNewDomainBtn-{{ $tenant->id }}"
            class="w-full bg-invert text-invert font-semibold py-3 border-rounded transition flex justify-center items-center gap-2">
            <span id="verifyBtnText-{{ $tenant->id }}">Verify Domain</span>
            <span id="verifySpinner-{{ $tenant->id }}" class="hidden loader"></span>
        </button>

    </div>
</div>

<style>
    .loader {
        border: 3px solid rgba(255,255,255,0.3);
        border-top: 3px solid currentColor;
        border-radius: 50%;
        width: 14px;
        height: 14px;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    (function() {
        const tenantId = "{{ $tenant->id }}";

        const openBtn   = document.getElementById("connectDomainBtn-" + tenantId);
        const popup     = document.getElementById("connectDomainPopup-" + tenantId);
        const closeBtn  = document.getElementById("closeDomainPopup-" + tenantId);
        const verifyBtn = document.getElementById("verifyNewDomainBtn-" + tenantId);
        const domainInput = document.getElementById("newCustomDomain-" + tenantId);
        const statusBox = document.getElementById("statusBox-" + tenantId);
        const btnText   = document.getElementById("verifyBtnText-" + tenantId);
        const spinner   = document.getElementById("verifySpinner-" + tenantId);

        if (!openBtn || !popup) return;

        function showStatus(type, message) {
            statusBox.classList.remove("hidden");
            statusBox.className = "p-3 border-rounded mb-4 text-sm";

            if (type === "success") {
                statusBox.classList.add("border", "border-green-500", "bg-green-50", "text-green-700");
            } else if (type === "error") {
                statusBox.classList.add("border", "border-red-500", "bg-red-50", "text-red-700");
            } else if (type === "wait") {
                statusBox.classList.add("border", "border-yellow-500", "bg-yellow-50", "text-yellow-700");
            }

            statusBox.innerHTML = message;
        }

        function setLoading(isLoading) {
            if (isLoading) {
                verifyBtn.disabled = true;
                spinner.classList.remove("hidden");
                btnText.textContent = "Verifying...";
            } else {
                verifyBtn.disabled = false;
                spinner.classList.add("hidden");
                btnText.textContent = "Verify Domain";
            }
        }

        openBtn.addEventListener("click", () => {
            popup.classList.remove("hidden");
            statusBox.classList.add("hidden");
            document.body.style.overflow = "hidden";
        });

        closeBtn.addEventListener("click", () => {
            popup.classList.add("hidden");
            document.body.style.overflow = "";
        });

        // Close on backdrop click
        popup.addEventListener("click", (e) => {
            if (e.target === popup) {
                popup.classList.add("hidden");
                document.body.style.overflow = "";
            }
        });

        verifyBtn.addEventListener("click", function() {
            const domain = domainInput.value.trim();
            if (!domain) return;

            showStatus("wait", "⏳ Checking DNS & SSL... Please wait...");
            setLoading(true);

            fetch(`{{ route('domain.verify', $tenant->id) }}?domain=${domain}`)
                .then(res => res.json())
                .then(data => {

                    // --- RATE LIMIT ---
                    if (data.status === "rate_limited") {
                        showStatus(
                            "error",
                            `⚠️ Too many SSL attempts.<br>Retry after: <b>${data.retry_ist}</b>`
                        );
                    }

                    // --- SUCCESS ---
                    else if (data.status === "success") {
                        showStatus("success", data.message);
                        setTimeout(() => location.reload(), 1500);
                    }

                    // --- ANY OTHER ERROR (SHOW RAW OUTPUT ALSO) ---
                    else {
                        let msg = data.message ?? "❌ SSL generation failed.";

                        if (data.raw_output) {
                            msg += `
                    <br><br>
                    <pre class="text-xs bg-gray-100 p-2 border rounded-md whitespace-pre-wrap">
${data.raw_output}
                    </pre>
                `;
                        }

                        showStatus("error", msg);
                    }

                    setLoading(false);
                })
                .catch((err) => {
                    showStatus("error", "❌ Something went wrong. Please retry.");
                    setLoading(false);
                });

        });
    })();
</script>
