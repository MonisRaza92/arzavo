<?php

namespace App\Services\Commerce;

use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\UserEntitlement;
use App\Models\Tenant\ProductVariant;
use App\Services\Payment\PaymentManager;
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
        $user = auth()->user();

        // 1. Calculate items & grand total
        $itemsData = $data['items'] ?? [];
        $subtotal = 0;

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user?->id,
            'customer_name' => $data['customer_name'] ?? ($user?->name ?? 'Customer'),
            'customer_email' => $data['customer_email'] ?? ($user?->email ?? ''),
            'customer_phone' => $data['customer_phone'] ?? null,
            'currency' => 'INR',
            'subtotal' => 0,
            'discount_amount' => 0,
            'shipping_amount' => $data['shipping_amount'] ?? 0,
            'grand_total' => 0,
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'unfulfilled',
            'payment_gateway' => $data['payment_gateway'] ?? 'cod',
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

            // Grant Instant Entitlements if item is free or digital instant access
            if ($user && ($orderItem->fulfillment_type === 'digital_download' || $orderItem->fulfillment_type === 'online_access')) {
                UserEntitlement::firstOrCreate([
                    'user_id' => $user->id,
                    'entitable_type' => $purchasableType,
                    'entitable_id' => $purchasableId,
                    'variant_id' => $variantId,
                ], [
                    'order_id' => $order->id,
                    'can_download' => $variant ? $variant->is_downloadable : true,
                    'can_stream_online' => $variant ? $variant->is_streamable : true,
                ]);
            }
        }

        $grandTotal = $subtotal + ($data['shipping_amount'] ?? 0);
        $order->update([
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
        ]);

        // 2. Execute Payment Driver
        $driver = $this->paymentManager->driver($order->payment_gateway);
        $paymentResult = $driver->processPayment($order, $data);

        return [
            'order' => $order,
            'payment' => $paymentResult,
        ];
    }
}
