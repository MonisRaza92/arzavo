<?php

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Services\Commerce\CheckoutService;
use App\Models\Tenant\Order;
use App\Models\Tenant\Book;
use App\Models\Tenant\Course;
use App\Models\Tenant\ProductVariant;
use Illuminate\Http\Request;

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

        if ($purchasableType && $purchasableId) {
            $modelClass = match ($purchasableType) {
                'book', 'Book', 'App\Models\Tenant\Book' => Book::class,
                'course', 'Course', 'App\Models\Tenant\Course' => Course::class,
                default => Book::class,
            };

            $item = $modelClass::find($purchasableId);
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
            } elseif ($item && method_exists($item, 'getDefaultVariant')) {
                $variant = $item->getDefaultVariant();
            }
        }

        return view("tenant.themes.checkout", compact('item', 'variant', 'purchasableType', 'purchasableId'));
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
        $payload['customer_name'] = $request->input('customer_name') ?: ($authUser?->name ?: 'Guest Student');
        $payload['customer_email'] = $request->input('customer_email') ?: ($authUser?->email ?: 'student@' . request()->getHost());
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

        return redirect()->away($result['payment']['redirect_url'] ?? route('checkout.success', $result['order']->order_number));
    }

    /**
     * Display Order Success Page.
     */
    public function success($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view("tenant.themes.success", compact('order'));
    }
}
