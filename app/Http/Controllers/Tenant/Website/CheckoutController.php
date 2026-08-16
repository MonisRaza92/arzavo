<?php

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Services\Commerce\CheckoutService;
use App\Models\Tenant\Order;
use App\Models\Tenant\Book;
use App\Models\Tenant\Course;
use App\Models\Tenant\ProductVariant;
use App\Models\Tenant\UserEntitlement;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Display Universal Checkout Page.
     */
    public function show(Request $request)
    {
        $purchasableType = $request->get('purchasable_type');
        $purchasableId = $request->get('purchasable_id');
        $variantId = $request->get('variant_id');

        $item = null;
        $variant = null;
        $modelClass = null;

        if ($purchasableType && $purchasableId) {
            $modelClass = match (strtolower($purchasableType)) {
                'book', 'books', 'app\models\tenant\book' => Book::class,
                'course', 'courses', 'app\models\tenant\course' => Course::class,
                default => Book::class,
            };

            $item = $modelClass::find($purchasableId);
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
            } elseif ($item && method_exists($item, 'getDefaultVariant')) {
                $variant = $item->getDefaultVariant();
            }
        }

        $authUser = auth('tenant')->user() ?? auth()->user();
        $isPurchased = false;

        if ($authUser && $item) {
            // Check UserEntitlement
            $isPurchased = UserEntitlement::where('user_id', $authUser->id)
                ->where('entitable_id', $item->id)
                ->where(function ($q) use ($modelClass) {
                    $q->where('entitable_type', $modelClass)
                      ->orWhere('entitable_type', class_basename($modelClass))
                      ->orWhere('entitable_type', strtolower(class_basename($modelClass)));
                })
                ->exists();

            // Check Course Enrollments
            if (!$isPurchased && ($modelClass === Course::class || $item instanceof Course)) {
                try {
                    $isPurchased = DB::connection('tenant')
                        ->table('course_enrollments')
                        ->where('user_id', $authUser->id)
                        ->where('course_id', $item->id)
                        ->where('status', 'active')
                        ->exists();
                } catch (\Throwable $e) {
                    // Ignore if table doesn't exist
                }
            }

            // Check previous paid orders
            if (!$isPurchased) {
                $isPurchased = \App\Models\Tenant\OrderItem::whereHas('order', function ($q) use ($authUser) {
                    $q->where('user_id', $authUser->id)
                      ->where('payment_status', 'paid');
                })
                ->where('purchasable_id', $item->id)
                ->exists();
            }
        }

        return view("tenant.themes.checkout", compact('item', 'variant', 'purchasableType', 'purchasableId', 'isPurchased', 'authUser'));
    }

    /**
     * Process checkout submission.
     */
    public function process(Request $request)
    {
        $authUser = auth('tenant')->user() ?? auth()->user();

        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'payment_gateway' => 'nullable|string',
        ]);

        $payload = $request->all();
        $payload['customer_name'] = $request->input('customer_name') ?: ($authUser ? ($authUser->name ?? trim(($authUser->fname ?? '') . ' ' . ($authUser->lname ?? ''))) : 'Student');
        $payload['customer_email'] = $request->input('customer_email') ?: ($authUser?->email ?? ('student@' . request()->getHost()));
        $payload['customer_phone'] = $request->input('customer_phone') ?: ($authUser?->number ?? $authUser?->phone ?? null);
        $payload['payment_gateway'] = $request->input('payment_gateway', 'razorpay');

        // Handle proof screenshot upload for manual bank transfer
        if ($request->hasFile('payment_proof_file')) {
            $path = $request->file('payment_proof_file')->store('payment_proofs', 'public');
            $payload['proof_file_path'] = $path;
        }

        $result = $this->checkoutService->processCheckout($payload);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result['payment']);
        }

        $gateway = $result['payment']['gateway'] ?? $payload['payment_gateway'];

        // 1. Razorpay JS Checkout Popup
        if ($gateway === 'razorpay' && !empty($result['payment']['key'])) {
            return view('tenant.themes.payment_razorpay', [
                'order' => $result['order'],
                'payment' => $result['payment'],
            ]);
        }

        // 2. Hosted form-post gateways (PayU, Paytm)
        if (!empty($result['payment']['action']) && !empty($result['payment']['params'])) {
            return view('tenant.themes.payment_redirect', [
                'action' => $result['payment']['action'],
                'params' => $result['payment']['params'],
                'gateway' => $gateway,
            ]);
        }

        // 3. External Redirect (Cashfree checkout link)
        if (!empty($result['payment']['redirect_url']) && str_starts_with($result['payment']['redirect_url'], 'http') && !str_contains($result['payment']['redirect_url'], route('checkout.success', $result['order']->order_number))) {
            return redirect()->away($result['payment']['redirect_url']);
        }

        return redirect()->route('checkout.success', $result['order']->order_number);
    }

    /**
     * Verify Razorpay Client Signature and confirm payment.
     */
    public function verifyRazorpay(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'razorpay_payment_id' => 'required|string',
        ]);

        $orderNumber = $request->input('order_number');
        $paymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        // Verify Razorpay signature if secret is present
        $secret = Settings::get('razorpay_webhook_secret', Settings::get('razorpay_secret', ''));
        $isValid = true;

        if ($razorpayOrderId && $signature && $secret) {
            $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $paymentId, $secret);
            $isValid = hash_equals($expectedSignature, $signature);
        }

        if (!$isValid) {
            Log::warning("Razorpay signature mismatch for Order #{$orderNumber}");
        }

        // Mark order as paid
        $order->update(['payment_status' => 'paid']);

        Transaction::updateOrCreate(
            [
                'order_id' => $order->id,
                'gateway' => 'razorpay',
                'transaction_id' => $paymentId,
            ],
            [
                'reference_number' => $razorpayOrderId ?: $paymentId,
                'type' => 'charge',
                'amount' => $order->grand_total,
                'currency' => $order->currency ?? 'INR',
                'status' => 'success',
                'gateway_payload' => $request->all(),
            ]
        );

        // Grant digital access
        CheckoutService::fulfillOrder($order);

        return redirect()->route('checkout.success', $order->order_number)->with('success', 'Payment successful!');
    }

    /**
     * Display Order Success Page.
     */
    public function success($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        // Only fulfill if payment is paid or order is free
        if ($order->payment_status === 'paid' || $order->grand_total <= 0) {
            CheckoutService::fulfillOrder($order);
        }

        return view('tenant.themes.success', compact('order'));
    }
}
