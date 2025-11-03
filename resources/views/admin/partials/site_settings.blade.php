<form action="{{ route('update-settings') }}" method="POST" enctype="multipart/form-data" class="my-4">
    @csrf

    <!-- General Settings -->
    <h2 class="text-lg font-semibold mb-2">General Settings</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Site Name</label>
            <input type="text" name="site_name" placeholder="Enter Your School/Coaching Name" value="{{ $settings['site_name'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Tagline</label>
            <input type="text" name="tagline" placeholder="Enter Your School/Coaching Tagline (Optional)" value="{{ $settings['tagline'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
    </div>

    <!-- SEO & Analytics -->
    <h2 class="text-lg font-semibold mb-2 mt-4">SEO & Analytics</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Meta Title</label>
            <input type="text" name="meta_title" placeholder="Enter Meta Title for SEO" value="{{ $settings['meta_title'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Meta Description</label>
            <textarea name="meta_description" placeholder="Enter Meta Description for SEO" class="w-full rounded-md p-2" style="background-color: var(--background-color);">{{ $settings['meta_description'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Google Analytics Code</label>
            <input type="text" name="google_analytics" placeholder="Enter Google Analytics Tracking Code" value="{{ $settings['google_analytics'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Facebook Pixel</label>
            <input type="text" name="facebook_pixel" placeholder="Enter Facebook Pixel Code" value="{{ $settings['facebook_pixel'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
    </div>

    <!-- Contact Settings -->
    <h2 class="text-lg font-semibold mb-2 mt-4">Contact Information</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Phone</label>
            <input type="text" name="phone" placeholder="Enter Your School/Coaching Phone Number" value="{{ $settings['phone'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" placeholder="Enter Your School/Coaching Email" value="{{ $settings['email'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Address</label>
            <textarea name="address" placeholder="Enter Your School/Coaching Address" class="w-full rounded-md p-2" style="background-color: var(--background-color);">{{ $settings['address'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Address Link</label>
            <input type="url" name="address_link" placeholder="Enter Google Maps Link for Address" value="{{ $settings['address_link'] ?? '' }}" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
        </div>
    </div>

    <!-- Social Media -->
    <h2 class="text-lg font-semibold mb-2 mt-4">Social Media</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
            $socials = [
            'facebook_url' => ['Facebook', 'fab fa-facebook text-blue-600'],
            'instagram_url' => ['Instagram', 'fab fa-instagram text-pink-500'],
            'twitter_url' => ['Twitter', 'fab fa-twitter text-sky-500'],
            'linkedin_url' => ['LinkedIn', 'fab fa-linkedin text-blue-700'],
            'youtube_url' => ['YouTube', 'fab fa-youtube text-red-600'],
            'whatsapp_url' => ['WhatsApp', 'fab fa-whatsapp text-green-500'],
            ];
            @endphp

            @foreach ($socials as $name => [$placeholder, $icon])
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="{{ $icon }}"></i>
                </span>
                <input type="url" name="{{ $name }}" placeholder="{{ $placeholder }}"
                    value="{{ $settings[$name] ?? '' }}"
                    class="w-full pl-10 rounded-md p-2" style="background-color: var(--background-color);">
            </div>
            @endforeach
        </div>
    </div>

    <!-- Website Controls -->
    <h2 class="text-lg font-semibold mb-2 mt-4">Website Controls</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color:var(--secondary-background);">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-gray-700">
            @php
            $controls = [
            'allow_registration' => 'Allow Registrations',
            'allow_admissions' => 'Allow Admissions',
            'show_events' => 'Show Events',
            'show_blog' => 'Show Blogs',
            'show_testimonials' => 'Show Testimonials',
            'allow_download_videos' => 'Allow Download Videos',
            'allow_download_pdf' => 'Allow Download PDF',
            'online_fees_collection' => 'Online Fees Collection',
            'cookies_consent' => 'Cookies Consent Banner',
            'maintenance_mode' => 'Maintenance Mode',
            ];
            @endphp

            @foreach ($controls as $name => $label)
            <label class="flex items-center justify-between cursor-pointer">
                <span style="color:var(--text-color);">{{ $label }}</span>
                <div class="relative">
                    <!-- hidden input for unchecked state -->
                    <input type="hidden" name="{{ $name }}" value="0">

                    <!-- actual toggle checkbox -->
                    <input type="checkbox" name="{{ $name }}" value="1"
                        {{ ($settings[$name] ?? false) ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                    <div
                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full border shadow-md peer-checked:translate-x-5 transition-transform">
                    </div>
                </div>
            </label>
            @endforeach
        </div>
    </div>


    <h2 class="text-lg font-semibold mb-2 mt-4">Communication</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">

        <div class="mb-6">
            <h3 class="font-medium mb-2 pb-2" style="border-bottom: 2px solid var(--border-color);">Email (SMTP) Settings</h3>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMTP Host</label>
                <input type="text" name="smtp_host" placeholder="Enter SMTP Host"
                    value="{{ $settings['smtp_host'] ?? '' }}" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMTP Port</label>
                <input type="text" name="smtp_port" placeholder="Enter SMTP Port"
                    value="{{ $settings['smtp_port'] ?? '' }}" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMTP Username</label>
                <input type="text" name="smtp_username" placeholder="Enter SMTP Username"
                    value="{{ $settings['smtp_username'] ?? '' }}" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMTP Password</label>
                <input type="password" name="smtp_password" placeholder="Enter SMTP Password"
                    value="{{ $settings['smtp_password'] ?? '' }}" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMTP Encryption</label>
                <select name="smtp_encryption" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
                    <option value="" {{ empty($settings['smtp_encryption']) ? 'selected' : '' }}>None</option>
                    <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="tls" {{ ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-medium mb-2 pb-2" style="border-bottom: 2px solid var(--border-color);">SMS Gateway Settings</h3>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMS Gateway Provider</label>
                <select name="sms_gateway_provider" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
                    <option value="">-- Select Provider --</option>
                    <option value="twilio" {{ ($settings['sms_gateway_provider'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                    <option value="msg91" {{ ($settings['sms_gateway_provider'] ?? '') === 'msg91' ? 'selected' : '' }}>Msg91</option>
                    <option value="other" {{ ($settings['sms_gateway_provider'] ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMS API Key</label>
                <input type="text" name="sms_api_key" placeholder="Enter SMS API Key"
                    value="{{ $settings['sms_api_key'] ?? '' }}" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">SMS Sender ID</label>
                <input type="text" name="sms_sender_id" placeholder="Enter SMS Sender ID"
                    value="{{ $settings['sms_sender_id'] ?? '' }}" class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
        </div>
        <div class="mb-3">
            <h3 class="font-medium mb-2 pb-2" style="border-bottom: 2px solid var(--border-color);">Send Notifications For:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold mb-2" style="color: var(--text-color);">Email</h4>
                    @php
                    $email_controls = [
                    'notify_on_registration_email' => 'New Registration',
                    'notify_on_admission_email' => 'New Admission',
                    'notify_on_payment_email' => 'Fee Payment Confirmation',
                    'notify_on_event_announcement_email' => 'Event Announcements',
                    'notify_on_course_announcement_email' => 'Course Announcements',
                    ];
                    @endphp
                    @foreach ($email_controls as $name => $label)
                    <label class="flex items-center justify-between cursor-pointer mb-2">
                        <span style="color: var(--text-color);">{{ $label }}</span>
                        <div class="relative">
                            <input type="hidden" name="{{ $name }}" value="0">
                            <input type="checkbox" name="{{ $name }}" value="1"
                                {{ ($settings[$name] ?? false) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                            <div
                                class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full border shadow-md peer-checked:translate-x-5 transition-transform">
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div>
                    <h4 class="font-semibold mb-2" style="color: var(--text-color);">SMS</h4>
                    @php
                    $sms_controls = [
                    'notify_on_registration_sms' => 'New Registration',
                    'notify_on_admission_sms' => 'New Admission',
                    'notify_on_payment_sms' => 'Fee Payment Confirmation',
                    'notify_on_event_announcement_sms' => 'Event Announcements',
                    'notify_on_course_announcement_sms' => 'Course Announcements',
                    ];
                    @endphp
                    @foreach ($sms_controls as $name => $label)
                    <label class="flex items-center justify-between cursor-pointer mb-2">
                        <span style="color: var(--text-color);">{{ $label }}</span>
                        <div class="relative">
                            <input type="hidden" name="{{ $name }}" value="0">
                            <input type="checkbox" name="{{ $name }}" value="1"
                                {{ ($settings[$name] ?? false) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                            <div
                                class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full border shadow-md peer-checked:translate-x-5 transition-transform">
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Gateway Settings -->
    <h2 class="text-lg font-semibold mb-2 mt-4">Payment Gateway Settings</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">

        <!-- Payment Gateway Selection -->
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Select Payment Gateway</label>
            <select id="payment_gateway" name="payment_gateway"
                class="w-full rounded-md p-2"
                style="background-color: var(--background-color);">
                <option value="">-- Select Gateway --</option>
                <option value="razorpay" {{ ($settings['payment_gateway'] ?? '') === 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                <option value="stripe" {{ ($settings['payment_gateway'] ?? '') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                <option value="paypal" {{ ($settings['payment_gateway'] ?? '') === 'paypal' ? 'selected' : '' }}>PayPal</option>
            </select>
        </div>

        <!-- Razorpay Fields -->
        <div id="razorpay_fields" class="gateway-fields hidden">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">Razorpay Key</label>
                <input type="text" name="razorpay_key" value="{{ $settings['razorpay_key'] ?? '' }}"
                    placeholder="Enter Razorpay Key"
                    class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">Razorpay Secret</label>
                <input type="text" name="razorpay_secret" value="{{ $settings['razorpay_secret'] ?? '' }}"
                    placeholder="Enter Razorpay Secret"
                    class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
        </div>

        <!-- Stripe Fields -->
        <div id="stripe_fields" class="gateway-fields hidden">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">Stripe Publishable Key</label>
                <input type="text" name="stripe_key" value="{{ $settings['stripe_key'] ?? '' }}"
                    placeholder="Enter Stripe Key"
                    class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">Stripe Secret Key</label>
                <input type="text" name="stripe_secret" value="{{ $settings['stripe_secret'] ?? '' }}"
                    placeholder="Enter Stripe Secret"
                    class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
        </div>

        <!-- PayPal Fields -->
        <div id="paypal_fields" class="gateway-fields hidden">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">PayPal Client ID</label>
                <input type="text" name="paypal_client_id" value="{{ $settings['paypal_client_id'] ?? '' }}"
                    placeholder="Enter PayPal Client ID"
                    class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">PayPal Secret</label>
                <input type="text" name="paypal_secret" value="{{ $settings['paypal_secret'] ?? '' }}"
                    placeholder="Enter PayPal Secret"
                    class="w-full rounded-md p-2"
                    style="background-color: var(--background-color);">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-2">PayPal Mode</label>
                <select name="paypal_mode" class="w-full rounded-md p-2" style="background-color: var(--background-color);">
                    <option value="sandbox" {{ ($settings['paypal_mode'] ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                    <option value="live" {{ ($settings['paypal_mode'] ?? '') === 'live' ? 'selected' : '' }}>Live</option>
                </select>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const gatewaySelect = document.getElementById("payment_gateway");
            const fields = document.querySelectorAll(".gateway-fields");

            function toggleFields() {
                fields.forEach(field => field.classList.add("hidden"));
                const selected = gatewaySelect.value;
                if (selected) {
                    document.getElementById(selected + "_fields").classList.remove("hidden");
                }
            }

            // Run on load (for pre-selected values)
            toggleFields();

            // Run on change
            gatewaySelect.addEventListener("change", toggleFields);
        });
    </script>



    <!-- Footer Settings -->
    <h2 class="text-lg font-semibold mb-2 mt-4">Footer Settings</h2>
    <div class="p-4 rounded-md shadow-sm" style="background-color: var(--secondary-background);">
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Footer Text</label>
            <input type="text" name="footer_text" placeholder="Enter Footer Text"
                value="{{ $settings['footer_text'] ?? '' }}"
                class="w-full rounded-md p-2"
                style="background-color: var(--background-color);">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Privacy Policy URL</label>
            <input type="url" name="privacy_policy_url" placeholder="Enter Privacy Policy Page URL"
                value="{{ $settings['privacy_policy_url'] ?? '' }}"
                class="w-full rounded-md p-2"
                style="background-color: var(--background-color);">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-2">Terms & Conditions URL</label>
            <input type="url" name="terms_url" placeholder="Enter Terms & Conditions Page URL"
                value="{{ $settings['terms_url'] ?? '' }}"
                class="w-full rounded-md p-2"
                style="background-color: var(--background-color);">
        </div>
        <div class="flex items-center space-x-2">
            <label class="flex items-center justify-start cursor-pointer space-x-4">
                <div class="relative">
                    <input type="checkbox" name="auto_update_year" value="1"
                        {{ ($settings['auto_update_year'] ?? false) ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                    <div
                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full border shadow-md peer-checked:translate-x-5 transition-transform">
                    </div>
                </div>
                <span class="text-sm">Auto-update Copyright Year</span>
            </label>
        </div>
    </div>


    <button type="submit" class="default-button font-bold px-4 py-2 rounded mt-4">Save Settings</button>
</form>