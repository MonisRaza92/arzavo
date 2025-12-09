<!-- 🔐 Connect Domain Popup -->
<div id="connectDomainPopup-{{ $tenant->id }}"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white border-rounded w-full max-w-4xl p-6 md:h-11/12 h-dvh relative overflow-auto scrollbar animate-fadeIn shadow-lg">

        <!-- Close Button -->
        <button id="closeDomainPopup"
            class="absolute right-4 top-6 text-gray-500 hover:text-black text-2xl">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- HEADER -->
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 flex items-center justify-center bg-primary bg-opacity-10 border-rounded">
                <i class="fa-solid fa-globe text-primary text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-primary">Connect Your Domain</h2>
                <p class="text-sm text-gray-600">Make your website live on your own custom domain.</p>
            </div>
        </div>

        <!-- Steps Box -->
        <div class="bg-gray-50 border border-primary border-opacity-30 rounded-md p-5 mb-6">

            <h3 class="text-primary font-semibold text-sm mb-3">How to connect your domain</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Step 1 -->
                <div class="bg-white border rounded-md p-4">
                    <p class="font-bold text-primary text-sm mb-2">1. Open your domain provider</p>

                    <ul class="text-xs text-gray-600 list-disc pl-4 space-y-1 mb-3">
                        <li>GoDaddy</li>
                        <li>Namecheap</li>
                        <li>Hostinger</li>
                        <li>or whichever provider you use</li>
                    </ul>

                    <div class="text-xs text-gray-600 space-y-1">
                        <p class="font-semibold text-primary">Inside your provider panel:</p>

                        <p>• Go to <b>DNS Management</b> or <b>DNS Zone Editor</b></p>
                        <p>• Sometimes it appears under:
                            <span class="text-gray-700">
                                <b>Domain Settings → Manage DNS</b>
                            </span>
                        </p>

                        <p>• Look for buttons like:
                            <span class="text-gray-700">
                                <b>Add Record</b> / <b>Create New DNS Record</b>
                            </span>
                        </p>
                    </div>
                </div>


                <!-- Step 2 -->
                <div class="bg-white border rounded-md p-4">
                    <p class="font-bold text-primary text-sm mb-1">2. Add these DNS Records</p>

                    <div class="space-y-2">

                        <div class="bg-gray-50 border p-2 rounded-md text-xs">
                            <strong>A Record:</strong><br>
                            @ → 15.207.111.21
                        </div>

                        <div class="bg-gray-50 border p-2 rounded-md text-xs">
                            <strong>CNAME:</strong><br>
                            verify → verify.{{ config('app.domain') }}
                        </div>

                        <div class="bg-gray-50 border p-2 rounded-md text-xs">
                            <strong>A Record (www):</strong><br>
                            www → 15.207.111.21
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Domain Display -->
        <div class="bg-gray-50 absolute opacity-0 border border-primary rounded-md p-3 mb-4">
            <p class="text-sm text-gray-700">
                <strong>Domain to verify:</strong>
                <span id="domainDisplay" class="text-primary font-semibold">
                    {{ $tenant->custom_domain ?? 'None' }}
                </span>
            </p>
        </div>
        <span class="text-sm text-primary font-semibold">Your Domain to verify</span>
        <input
            type="text"
            id="newCustomDomain"
            value="{{ $tenant->custom_domain }}"
            class="w-full text-primary mt-2 border-rounded p-3 mb-4 border-primary"
            placeholder="Enter Your Domian"
            />
        <input type="hidden" value="{{ $tenant->id }}" name="id">

        <!-- Status Box -->
        <div id="statusBox" class="hidden p-3 border rounded-md mb-4"></div>

        <!-- Verify Button -->
        <button id="verifyNewDomainBtn"
            class="w-full bg-invert text-invert font-semibold py-3 border-rounded transition flex justify-center items-center gap-2">
            <span id="verifyBtnText">Verify Domain</span>
            <span id="verifySpinner" class="hidden loader"></span>
        </button>

    </div>
</div>

<style>
    .loader {
        border: 3px solid #f3f3f3;
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
    document.addEventListener("turbo:load", function() {
        const openBtn = document.getElementById("connectDomainBtn-{{ $tenant->id }}");
        const popup = document.getElementById("connectDomainPopup-{{ $tenant->id }}");
        const closeBtn = document.getElementById("closeDomainPopup");
        const verifyBtn = document.getElementById("verifyNewDomainBtn");
        const domainInput = document.getElementById("newCustomDomain");
        const statusBox = document.getElementById("statusBox");
        const btnText = document.getElementById("verifyBtnText");
        const spinner = document.getElementById("verifySpinner");

        if (!openBtn) return;

        function showStatus(type, message) {
            statusBox.classList.remove("hidden");
            statusBox.className = "p-3 border rounded-md mb-4";

            if (type === "success") {
                statusBox.classList.add("border-green-500", "bg-green-50", "text-green-700");
            } else if (type === "error") {
                statusBox.classList.add("border-red-500", "bg-red-50", "text-red-700");
            } else if (type === "wait") {
                statusBox.classList.add("border-yellow-500", "bg-yellow-50", "text-yellow-700");
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
        });

        closeBtn.addEventListener("click", () => popup.classList.add("hidden"));

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
    });
</script>