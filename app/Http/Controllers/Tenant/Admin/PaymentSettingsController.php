<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Settings;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    /**
     * Display Payment Settings and Webhook Setup Guides.
     */
    public function index()
    {
        $settings = Settings::pluck('value', 'key')->toArray();
        $webhookUrl = tenant_url() . '/api/v1/payments/webhook';

        return view('tenant.admin.settings.payments.index', compact('settings', 'webhookUrl'));
    }

    /**
     * Save Payment Settings.
     */
    public function store(Request $request)
    {
        $keys = [
            'payment_mode_online',
            'payment_mode_cod',
            'payment_mode_manual',
            
            'razorpay_enabled',
            'razorpay_key',
            'razorpay_secret',
            'razorpay_webhook_secret',
            
            'cashfree_enabled',
            'cashfree_app_id',
            'cashfree_secret_key',
            'cashfree_webhook_secret',
            'cashfree_environment',
            
            'payu_enabled',
            'payu_merchant_key',
            'payu_salt',
            'payu_webhook_salt',
            
            'paytm_enabled',
            'paytm_merchant_id',
            'paytm_merchant_key',
            'paytm_website',
            'paytm_channel_id',
            
            'manual_payment_bank_name',
            'manual_payment_bank_holder',
            'manual_payment_bank_account',
            'manual_payment_bank_ifsc',
            'manual_payment_bank_swift',
            'manual_payment_bank_address',
            'manual_payment_upi_id',
        ];

        $toggles = [
            'payment_mode_online',
            'payment_mode_cod',
            'payment_mode_manual',
            'razorpay_enabled',
            'cashfree_enabled',
            'payu_enabled',
            'paytm_enabled',
        ];

        // Ensure at least one primary payment mode is enabled
        if (!$request->has('payment_mode_online') && !$request->has('payment_mode_cod') && !$request->has('payment_mode_manual')) {
            return back()->withErrors(['error' => 'You cannot disable all payment types. At least one payment mode must be enabled.'])->withInput();
        }

        foreach ($keys as $key) {
            $value = $request->input($key);
            if (in_array($key, $toggles)) {
                $value = $request->has($key) ? $request->input($key) : '0';
            }
            
            Settings::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Payment Settings and Gateway configurations updated successfully!');
    }
}
