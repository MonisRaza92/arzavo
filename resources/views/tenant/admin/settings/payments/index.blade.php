@extends('layouts.admin')

@section('title', 'Payment Gateway Settings')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-credit-card text-primary text-base"></i>
            Payment Gateways & Modes
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Configure online payment gateways, Cash on Delivery, and Manual UPI/Bank QR transfers.</p>
    </div>
</div>

{{-- Validation Error Alert --}}
@if($errors->any())
<div class="bg-red-500/10 border border-red-500 text-red-500 text-xs p-3 rounded-lg mb-4">
    {{ $errors->first() }}
</div>
@endif

<form action="{{ route('admin.settings.payments.store') }}" method="POST" class="space-y-4" id="payment-settings-form">
    @csrf

    {{-- 1. PAYMENT MODES TOGGLES --}}
    <div class="bg-primary border-rounded border-primary mb-4">
        <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
            <i class="fa-solid fa-toggle-on text-primary text-sm"></i>
            Enabled Payment Modes
        </h2>
        
        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-input.toggle name="payment_mode_online" id="payment_mode_online" label="Online Gateways" :value="($settings['payment_mode_online'] ?? '1') == '1'" hint="Accept payments via Razorpay, Cashfree, PayU, Paytm etc." />
            <x-input.toggle name="payment_mode_cod" id="payment_mode_cod" label="Cash on Delivery (COD)" :value="($settings['payment_mode_cod'] ?? '0') == '1'" hint="Collect payment upon delivery" />
            <x-input.toggle name="payment_mode_manual" id="payment_mode_manual" label="Manual Bank / UPI QR" :value="($settings['payment_mode_manual'] ?? '1') == '1'" hint="Allow direct bank transfer or UPI" />
        </div>
    </div>

    {{-- ONLINE GATEWAYS WRAPPER (HIDDEN IF ONLINE MODE IS OFF) --}}
    <div id="online-gateways-wrapper" class="space-y-4 hidden">
        {{-- 2. GATEWAY SELECTION TOGGLES --}}
        <div class="bg-primary border-rounded border-primary mb-4">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-money-bill-wave text-primary text-sm"></i>
                Online Gateways Activation
            </h2>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Razorpay Enable --}}
                <div class="flex items-center justify-between p-3 rounded-lg border border-primary bg-secondary/30">
                    <div class="max-w-[70%] flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-primary text-base"></i>
                        <div>
                            <label for="razorpay_enabled" class="text-sm font-bold text-primary block">Razorpay</label>
                            <p class="text-[11px] text-secondary">Accept card, UPI, netbanking payments.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="razorpay_enabled" id="razorpay_enabled" value="1" 
                            {{ ($settings['razorpay_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-black transition-colors duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>

                {{-- Cashfree Enable --}}
                <div class="flex items-center justify-between p-3 rounded-lg border border-primary bg-secondary/30">
                    <div class="max-w-[70%] flex items-center gap-2">
                        <i class="fa-solid fa-money-bill-transfer text-primary text-base"></i>
                        <div>
                            <label for="cashfree_enabled" class="text-sm font-bold text-primary block">Cashfree</label>
                            <p class="text-[11px] text-secondary">Fast checkout with split payments support.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="cashfree_enabled" id="cashfree_enabled" value="1" 
                            {{ ($settings['cashfree_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-black transition-colors duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>

                {{-- PayU Enable --}}
                <div class="flex items-center justify-between p-3 rounded-lg border border-primary bg-secondary/30">
                    <div class="max-w-[70%] flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-primary text-base"></i>
                        <div>
                            <label for="payu_enabled" class="text-sm font-bold text-primary block">PayU India</label>
                            <p class="text-[11px] text-secondary">Accept direct web payments via PayU.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="payu_enabled" id="payu_enabled" value="1" 
                            {{ ($settings['payu_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-black transition-colors duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>

                {{-- Paytm Enable --}}
                <div class="flex items-center justify-between p-3 rounded-lg border border-primary bg-secondary/30">
                    <div class="max-w-[70%] flex items-center gap-2">
                        <i class="fa-solid fa-wallet text-primary text-base"></i>
                        <div>
                            <label for="paytm_enabled" class="text-sm font-bold text-primary block">Paytm</label>
                            <p class="text-[11px] text-secondary">Accept payments using Paytm app wallets & instruments.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="paytm_enabled" id="paytm_enabled" value="1" 
                            {{ ($settings['paytm_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-black transition-colors duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- 3. RAZORPAY CONFIGURATION --}}
        <div id="config-razorpay" class="bg-primary border-rounded border-primary mb-4 hidden">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-primary text-sm"></i>
                Razorpay Gateway Configuration
            </h2>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-input.text name="razorpay_key" label="Razorpay Key ID" :value="$settings['razorpay_key'] ?? ''" placeholder="rzp_live_XXXXXXXX" hint="Enter key ID from your Razorpay Dashboard API keys page." />
                </div>
                <div class="w-full">
                    <x-input.password name="razorpay_secret" label="Razorpay Key Secret" :value="$settings['razorpay_secret'] ?? ''" hint="Enter Key Secret provided when API key was generated." />
                </div>
                <div class="w-full md:col-span-2">
                    <x-input.password name="razorpay_webhook_secret" label="Razorpay Webhook Secret" :value="$settings['razorpay_webhook_secret'] ?? ''" hint="Webhook secret verification code to secure payment status updates." />
                </div>
            </div>
        </div>

        {{-- 4. CASHFREE CONFIGURATION --}}
        <div id="config-cashfree" class="bg-primary border-rounded border-primary mb-4 hidden">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-money-bill-transfer text-primary text-sm"></i>
                Cashfree Gateway Configuration
            </h2>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-input.text name="cashfree_app_id" label="Cashfree App ID" :value="$settings['cashfree_app_id'] ?? ''" placeholder="Enter Cashfree App ID..." hint="Your Cashfree merchant Account App ID." />
                </div>
                <div class="w-full">
                    <x-input.password name="cashfree_secret_key" label="Cashfree Secret Key" :value="$settings['cashfree_secret_key'] ?? ''" hint="Merchant secret API key to sign requests." />
                </div>
                <div class="w-full">
                    <x-input.password name="cashfree_webhook_secret" label="Cashfree Webhook Signature Verification Key" :value="$settings['cashfree_webhook_secret'] ?? ''" hint="Used to authenticate status callback alerts." />
                </div>
                <div class="w-full">
                    <x-input.select name="cashfree_environment" label="Cashfree Environment" :value="$settings['cashfree_environment'] ?? 'sandbox'" :options="['sandbox' => 'Sandbox / Testing', 'production' => 'Production / Live']" hint="Select 'Sandbox' for testing transactions and 'Production' for actual payments." />
                </div>
            </div>
        </div>

        {{-- 5. PAYU CONFIGURATION --}}
        <div id="config-payu" class="bg-primary border-rounded border-primary mb-4 hidden">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-primary text-sm"></i>
                PayU Gateway Configuration
            </h2>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-input.text name="payu_merchant_key" label="PayU Merchant Key" :value="$settings['payu_merchant_key'] ?? ''" placeholder="Merchant Key..." hint="Merchant identifier key provided in PayU console." />
                </div>
                <div class="w-full">
                    <x-input.password name="payu_salt" label="PayU Salt" :value="$settings['payu_salt'] ?? ''" hint="Cryptographic SALT used to secure web checkouts." />
                </div>
                <div class="w-full md:col-span-2">
                    <x-input.password name="payu_webhook_salt" label="PayU Webhook Salt" :value="$settings['payu_webhook_salt'] ?? ''" hint="Verify payment completion notifications security." />
                </div>
            </div>
        </div>

        {{-- 6. PAYTM CONFIGURATION --}}
        <div id="config-paytm" class="bg-primary border-rounded border-primary mb-4 hidden">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet text-primary text-sm"></i>
                Paytm Gateway Configuration
            </h2>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-input.text name="paytm_merchant_id" label="Paytm Merchant ID" :value="$settings['paytm_merchant_id'] ?? ''" placeholder="Merchant ID..." hint="MID code allocated by Paytm Payment Gateway." />
                </div>
                <div class="w-full">
                    <x-input.password name="paytm_merchant_key" label="Paytm Merchant Key" :value="$settings['paytm_merchant_key'] ?? ''" hint="Secure merchant key for credentials checksum authentication." />
                </div>
                <div class="w-full">
                    <x-input.text name="paytm_website" label="Paytm Website Name" :value="$settings['paytm_website'] ?? 'DEFAULT'" placeholder="e.g. WEBSTAGING / DEFAULT" hint="Paytm channel website code (usually DEFAULT or WEBSTAGING)." />
                </div>
                <div class="w-full">
                    <x-input.text name="paytm_channel_id" label="Paytm Channel ID" :value="$settings['paytm_channel_id'] ?? 'WEB'" placeholder="e.g. WEB / WAP" hint="Paytm request channel (WEB for desktop, WAP for app)." />
                </div>
            </div>
        </div>

        {{-- WEBHOOK SETUP GUIDE --}}
        <div class="bg-primary border-rounded border-primary p-4 mb-4 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-primary text-sm flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-link text-primary"></i> Gateway Webhook Setup URL & Instructions
                    </h3>
                    <p class="text-xs text-secondary leading-relaxed">Webhooks automatically grant instant access and update orders in real-time when a student pays via any gateway.</p>
                </div>
                <div>
                    @if(config('app.env') === 'production')
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                            <i class="fa-solid fa-circle-dot mr-1"></i> Live Gateway Mode
                        </span>
                    @else
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20">
                            <i class="fa-solid fa-flask mr-1"></i> Test Sandbox Mode
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center gap-2 bg-secondary p-3 rounded-lg border border-primary font-mono text-xs text-primary">
                <input type="text" readonly value="{{ $webhookUrl }}" id="webhook-url-input" class="w-full bg-transparent outline-none">
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('webhook-url-input').value); alert('Webhook URL Copied!')" class="p-2 bg-primary text-primary rounded-md border border-primary hover:opacity-90 transition flex items-center justify-center shrink-0" title="Copy Webhook URL">
                    <i class="fa-regular fa-copy text-primary text-base"></i>
                </button>
            </div>

            <div class="border-bottom my-2"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-bold text-primary mb-1">Why is this webhook required?</h4>
                    <p class="text-[11px] text-secondary leading-relaxed">
                        Jab koi student UPI, Card ya NetBanking se payment complete karta hai, gateway aapke server ke is URL par instant notification bhejta hai. Server order ko instant <strong>Paid</strong> mark karke student ko course ya book ka digital access provide kar deta hai.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-primary mb-1">Gateway-Specific Webhook Settings:</h4>
                    <ul class="list-disc pl-4 text-[11px] text-secondary space-y-1">
                        <li><strong>Razorpay:</strong> Razorpay Dashboard > Settings > Webhooks me yeh URL dalein, aur event <code>payment.captured</code> select karein.</li>
                        <li><strong>Cashfree:</strong> Cashfree Dashboard > Developers > Webhooks me yeh URL dalein for payment capture notifications.</li>
                        <li><strong>PayU India:</strong> PayU Merchant Center > Developers > Webhooks me yeh URL dalein for <code>payments.successful</code>.</li>
                        <li><strong>Paytm:</strong> Paytm Dashboard > Notification Preferences / Webhooks me configure karein.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- MANUAL BANK WRAPPER (HIDDEN IF MANUAL BANK IS OFF) --}}
    <div id="manual-payment-wrapper" class="bg-primary border-rounded border-primary mb-4 hidden">
        <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
            <i class="fa-solid fa-qrcode text-primary text-sm"></i>
            Manual Bank Transfer & UPI Details
        </h2>
        
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="w-full">
                <x-input.text name="manual_payment_bank_name" label="Bank Name" :value="$settings['manual_payment_bank_name'] ?? ''" placeholder="e.g. State Bank of India" hint="The registered name of the commercial bank." />
            </div>
            <div class="w-full">
                <x-input.text name="manual_payment_bank_holder" label="Account Holder Name" :value="$settings['manual_payment_bank_holder'] ?? ''" placeholder="e.g. John Doe" hint="The official name registered under the bank account." />
            </div>
            <div class="w-full">
                <x-input.text name="manual_payment_bank_account" label="Bank Account Number" :value="$settings['manual_payment_bank_account'] ?? ''" placeholder="e.g. 1234567890" hint="Account number for incoming wire transfers." />
            </div>
            <div class="w-full">
                <x-input.text name="manual_payment_bank_ifsc" label="IFSC Code" :value="$settings['manual_payment_bank_ifsc'] ?? ''" placeholder="e.g. SBIN0001234" hint="11-character Indian Financial System Code." />
            </div>
            <div class="w-full">
                <x-input.text name="manual_payment_bank_swift" label="Swift / BIC Code (Optional)" :value="$settings['manual_payment_bank_swift'] ?? ''" placeholder="e.g. SBININBBXXX" hint="Swift code for accepting international transfers." />
            </div>
            <div class="w-full">
                <x-input.text name="manual_payment_upi_id" label="UPI ID (Optional)" :value="$settings['manual_payment_upi_id'] ?? ''" placeholder="e.g. yourname@upi" hint="Direct UPI address to accept instant scan and pay payments." />
            </div>
            <div class="w-full md:col-span-2">
                <x-input.textarea name="manual_payment_bank_address" label="Bank Branch Address" :value="$settings['manual_payment_bank_address'] ?? ''" rows="2" placeholder="Enter branch street address, city, country..." hint="Physical branch location address of the bank." />
            </div>
        </div>
    </div>

    <div class="flex justify-end mb-12">
        <button type="submit" class="primary-button font-bold flex items-center gap-2 text-sm">
            <i class="fa-solid fa-floppy-disk text-base"></i>
            Save Payment Configurations
        </button>
    </div>
</form>

<script>
    document.addEventListener("turbo:load", () => {
        const onlineToggle = document.getElementById('payment_mode_online');
        const codToggle = document.getElementById('payment_mode_cod');
        const manualToggle = document.getElementById('payment_mode_manual');

        const onlineWrapper = document.getElementById('online-gateways-wrapper');
        const manualWrapper = document.getElementById('manual-payment-wrapper');

        // Main mode visibility toggles
        const updateLayout = () => {
            if (onlineToggle && onlineWrapper) {
                if (onlineToggle.checked) {
                    onlineWrapper.classList.remove('hidden');
                } else {
                    onlineWrapper.classList.add('hidden');
                }
            }
            if (manualToggle && manualWrapper) {
                if (manualToggle.checked) {
                    manualWrapper.classList.remove('hidden');
                } else {
                    manualWrapper.classList.add('hidden');
                }
            }
        };

        // Safety check to ensure at least one toggle is always ON
        const validateToggles = (changedToggle) => {
            if (!onlineToggle.checked && !codToggle.checked && !manualToggle.checked) {
                changedToggle.checked = true;
                alert('You cannot disable all payment types. At least one payment mode must be enabled.');
            }
        };

        if (onlineToggle && codToggle && manualToggle) {
            onlineToggle.addEventListener('change', function() {
                validateToggles(this);
                updateLayout();
            });
            codToggle.addEventListener('change', function() {
                validateToggles(this);
                updateLayout();
            });
            manualToggle.addEventListener('change', function() {
                validateToggles(this);
                updateLayout();
            });
        }

        // Inner Gateway toggles handler
        const toggleConfig = (toggleId, containerId) => {
            const toggle = document.getElementById(toggleId);
            const container = document.getElementById(containerId);
            if (toggle && container) {
                const updateState = () => {
                    if (toggle.checked) {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                };
                updateState();
                toggle.addEventListener('change', updateState);
            }
        };

        toggleConfig('razorpay_enabled', 'config-razorpay');
        toggleConfig('cashfree_enabled', 'config-cashfree');
        toggleConfig('payu_enabled', 'config-payu');
        toggleConfig('paytm_enabled', 'config-paytm');

        // Initial setup
        updateLayout();
    });
</script>
@endsection
