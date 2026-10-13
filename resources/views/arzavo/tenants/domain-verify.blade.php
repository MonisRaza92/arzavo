<!-- 🔐 Connect Domain Popup -->
<div id="connectDomainPopup-{{ $tenant->id }}"
    class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[999] p-4">

    <!-- Modal Block (bg-primary, rounded-lg, shadow-2xl) -->
    <div class="bg-primary rounded-lg w-full max-w-xl p-6 md:h-auto max-h-[90vh] relative overflow-y-auto shadow-2xl">

        <!-- Close Button -->
        <button id="closeDomainPopup-{{ $tenant->id }}"
            class="absolute right-4 top-4 w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- HEADER -->
        <div class="flex items-center gap-3.5 mb-5">
            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-secondary text-theme-text text-base shrink-0">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-theme-text">Connect Custom Domain</h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">Add these records to your domain DNS settings (no IP address required).</p>
            </div>
        </div>

        @php
            $targetDomain = env('CUSTOM_DOMAIN_TARGET', 'domain.arzavo.com');
            $verifyDomain = env('VERIFY_DOMAIN_TARGET', 'verify.arzavo.com');
        @endphp

        <!-- Simple DNS Records Guide -->
        <div class="space-y-3 mb-5">
            <!-- Step 1: Open Registrar -->
            <div class="bg-secondary rounded-lg p-3.5">
                <p class="font-semibold text-theme-text text-xs mb-1">Step 1: Open Domain DNS Settings</p>
                <p class="text-xs text-theme-text-muted font-normal leading-relaxed">
                    Log in to your domain registrar (GoDaddy, Namecheap, Hostinger, or Cloudflare) and open the <span class="text-theme-text font-medium">DNS Management</span> page.
                </p>
            </div>

            <!-- Step 2: Add 3 Records -->
            <div class="bg-secondary rounded-lg p-3.5">
                <p class="font-semibold text-theme-text text-xs mb-2.5">Step 2: Add These 3 DNS Records</p>

                <div class="space-y-2 text-xs">
                    <!-- Record 1: Root Domain CNAME / ALIAS -->
                    <div class="bg-primary rounded-lg p-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full bg-brand text-primary text-[10px] font-normal">CNAME / ALIAS</span>
                                <span class="text-theme-text-muted text-[11px] font-normal">Host: <strong class="text-theme-text font-mono font-normal">@</strong></span>
                            </div>
                            <div class="text-xs font-mono text-theme-text mt-1.5 truncate">
                                Points to: <span class="font-semibold text-theme-text">{{ $targetDomain }}</span>
                            </div>
                        </div>
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $targetDomain }}'); this.innerHTML = '<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML = '<i class=\'fa-regular fa-copy\'></i> Copy', 2000)"
                                class="px-3 py-1.5 bg-secondary text-theme-text-muted hover:text-theme-text rounded-full text-xs font-normal transition shrink-0 inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-copy"></i>
                            <span>Copy</span>
                        </button>
                    </div>

                    <!-- Record 2: WWW CNAME -->
                    <div class="bg-primary rounded-lg p-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full bg-secondary text-theme-text-muted text-[10px] font-normal">CNAME</span>
                                <span class="text-theme-text-muted text-[11px] font-normal">Host: <strong class="text-theme-text font-mono font-normal">www</strong></span>
                            </div>
                            <div class="text-xs font-mono text-theme-text mt-1.5 truncate">
                                Points to: <span class="font-semibold text-theme-text">{{ $targetDomain }}</span>
                            </div>
                        </div>
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $targetDomain }}'); this.innerHTML = '<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML = '<i class=\'fa-regular fa-copy\'></i> Copy', 2000)"
                                class="px-3 py-1.5 bg-secondary text-theme-text-muted hover:text-theme-text rounded-full text-xs font-normal transition shrink-0 inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-copy"></i>
                            <span>Copy</span>
                        </button>
                    </div>

                    <!-- Record 3: Verify TXT Record -->
                    <div class="bg-primary rounded-lg p-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full bg-secondary text-theme-text-muted text-[10px] font-normal">TXT Record</span>
                                <span class="text-theme-text-muted text-[11px] font-normal">Host: <strong class="text-theme-text font-mono font-normal">@</strong> <span class="text-[10px] text-theme-text-muted">(or verify)</span></span>
                            </div>
                            <div class="text-xs font-mono text-theme-text mt-1.5 truncate">
                                Value: <span class="font-semibold text-theme-text">{{ $verifyDomain }}</span>
                            </div>
                        </div>
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $verifyDomain }}'); this.innerHTML = '<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML = '<i class=\'fa-regular fa-copy\'></i> Copy', 2000)"
                                class="px-3 py-1.5 bg-secondary text-theme-text-muted hover:text-theme-text rounded-full text-xs font-normal transition shrink-0 inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-copy"></i>
                            <span>Copy</span>
                        </button>
                    </div>
                </div>

                <p class="text-[11px] text-theme-text-muted font-normal mt-2.5">
                    <i class="fa-solid fa-shield-halved text-[10px] text-success mr-1"></i>
                    After adding these records, click "Verify Domain & Issue SSL". A dedicated SSL certificate will be automatically issued.
                </p>
            </div>
        </div>

        <!-- Domain Input -->
        <label class="text-xs text-theme-text font-normal block mb-1.5">Your Custom Domain</label>
        <input
            type="text"
            id="newCustomDomain-{{ $tenant->id }}"
            value="{{ $tenant->custom_domain }}"
            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full mb-4 focus:outline-none"
            placeholder="e.g. youracademy.com or lms.youracademy.com"
        />
        <input type="hidden" value="{{ $tenant->id }}" name="id">

        <!-- Status Box -->
        <div id="statusBox-{{ $tenant->id }}" class="hidden p-3 rounded-lg mb-4 text-xs font-normal"></div>

        <!-- Verify Button -->
        <button id="verifyNewDomainBtn-{{ $tenant->id }}"
            class="w-full bg-brand text-primary font-normal py-3 px-6 rounded-full text-xs transition flex justify-center items-center gap-2 hover:opacity-90">
            <span id="verifyBtnText-{{ $tenant->id }}">Verify Domain & Issue SSL</span>
            <span id="verifySpinner-{{ $tenant->id }}" class="hidden w-3.5 h-3.5 border-2 border-primary/30 border-t-primary rounded-full animate-spin"></span>
        </button>

    </div>
</div>

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
            statusBox.className = "p-3 rounded-lg mb-4 text-xs font-normal";

            if (type === "success") {
                statusBox.classList.add("bg-success-bg", "text-success");
            } else if (type === "error") {
                statusBox.classList.add("bg-danger-bg", "text-danger");
            } else if (type === "wait") {
                statusBox.classList.add("bg-warning-bg", "text-warning");
            }

            statusBox.innerHTML = message;
        }

        function setLoading(isLoading) {
            if (isLoading) {
                verifyBtn.disabled = true;
                spinner.classList.remove("hidden");
                btnText.textContent = "Verifying DNS & SSL...";
            } else {
                verifyBtn.disabled = false;
                spinner.classList.add("hidden");
                btnText.textContent = "Verify Domain & Issue SSL";
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

        popup.addEventListener("click", (e) => {
            if (e.target === popup) {
                popup.classList.add("hidden");
                document.body.style.overflow = "";
            }
        });

        verifyBtn.addEventListener("click", function() {
            const domain = domainInput.value.trim();
            if (!domain) return;

            showStatus("wait", "Checking DNS records & generating SSL certificate... Please wait...");
            setLoading(true);

            fetch(`{{ route('domain.verify', $tenant->id) }}?domain=${domain}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "rate_limited") {
                        showStatus("error", `Too many SSL attempts. Retry after: ${data.retry_ist}`);
                    } else if (data.status === "success") {
                        showStatus("success", data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        let msg = data.message ?? "SSL generation failed.";
                        if (data.raw_output) {
                            msg += `<pre class="text-[11px] bg-secondary p-2 rounded-lg mt-2 whitespace-pre-wrap">${data.raw_output}</pre>`;
                        }
                        showStatus("error", msg);
                    }
                    setLoading(false);
                })
                .catch((err) => {
                    showStatus("error", "Something went wrong. Please check DNS and retry.");
                    setLoading(false);
                });
        });
    })();
</script>
