<?php

namespace App\Services\Commerce;

use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\UserEntitlement;
use App\Models\Tenant\ProductVariant;
use App\Models\Tenant\Course;
use App\Models\Tenant\Book;
use App\Services\Payment\PaymentManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CheckoutService
{
    protected PaymentManager $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Process checkout and create order ledger.
     */
    public function processCheckout(array $data): array
    {
        $orderNumber = 'ORD-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);
        $user = auth('tenant')->user() ?? auth()->user();

        $customerName = $data['customer_name'] ?: ($user ? ($user->name ?? trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''))) : 'Student');
        if (empty($customerName)) {
            $customerName = 'Student';
        }
        $customerEmail = $data['customer_email'] ?: ($user?->email ?? ('student@' . request()->getHost()));
        $customerPhone = $data['customer_phone'] ?: ($user?->number ?? $user?->phone ?? null);

        // 1. Calculate items & grand total
        $itemsData = $data['items'] ?? [];
        $subtotal = 0;

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user?->id,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'currency' => 'INR',
            'subtotal' => 0,
            'discount_amount' => 0,
            'shipping_amount' => $data['shipping_amount'] ?? 0,
            'grand_total' => 0,
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'unfulfilled',
            'payment_gateway' => $data['payment_gateway'] ?? 'razorpay',
            'shipping_address' => $data['shipping_address'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($itemsData as $item) {
            $purchasableType = $item['purchasable_type'];
            $purchasableId = $item['purchasable_id'];
            $variantId = $item['variant_id'] ?? null;
            $qty = (int) ($item['quantity'] ?? 1);

            $variant = $variantId ? ProductVariant::find($variantId) : null;
            $unitPrice = $variant ? $variant->price : ($item['unit_price'] ?? 0);
            $itemTotal = $unitPrice * $qty;
            $subtotal += $itemTotal;

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'purchasable_type' => $purchasableType,
                'purchasable_id' => $purchasableId,
                'variant_id' => $variantId,
                'item_name' => $item['item_name'] ?? 'Item',
                'sku' => $variant?->sku,
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'total_price' => $itemTotal,
                'fulfillment_type' => $variant?->fulfillment_type ?? ($item['fulfillment_type'] ?? 'digital_download'),
                'options_snapshot' => $variant?->attributes,
            ]);
        }

        $grandTotal = $subtotal + ($data['shipping_amount'] ?? 0);
        $order->update([
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
        ]);

        // Auto-fulfill free orders immediately
        if ($grandTotal <= 0) {
            $order->update(['payment_status' => 'paid']);
            static::fulfillOrder($order);
        }

        // 2. Execute Payment Driver
        $driver = $this->paymentManager->driver($order->payment_gateway);
        $paymentResult = $driver->processPayment($order, $data);

        return [
            'order' => $order,
            'payment' => $paymentResult,
        ];
    }

    /**
     * Fulfill order: Grant entitlements and enrollments to student.
     */
    public static function fulfillOrder(Order $order): void
    {
        if (!$order->user_id) {
            return;
        }

        $order->loadMissing('items');

        foreach ($order->items as $item) {
            $rawType = strtolower(class_basename($item->purchasable_type));
            $isCourse = in_array($rawType, ['course', 'courses', 'app\models\tenant\course'], true);
            $isBook = in_array($rawType, ['book', 'books', 'app\models\tenant\book'], true);

            $modelClass = $isCourse ? Course::class : ($isBook ? Book::class : $item->purchasable_type);

            // Grant Entitlement
            UserEntitlement::updateOrCreate([
                'user_id' => $order->user_id,
                'entitable_type' => $modelClass,
                'entitable_id' => $item->purchasable_id,
            ], [
                'order_id' => $order->id,
                'variant_id' => $item->variant_id,
                'can_download' => true,
                'can_stream_online' => true,
            ]);

            // If Course, also enroll student in course_enrollments
            if ($isCourse) {
                try {
                    DB::connection('tenant')->table('course_enrollments')->updateOrInsert(
                        [
                            'course_id' => $item->purchasable_id,
                            'user_id' => $order->user_id,
                        ],
                        [
                            'status' => 'active',
                            'enrolled_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                } catch (\Throwable $e) {
                    // Fail gracefully if table not present or constraints matched
                }
            }
        }

        $order->update(['fulfillment_status' => 'fulfilled']);
    }
}
