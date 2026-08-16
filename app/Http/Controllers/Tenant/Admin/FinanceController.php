<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\UserEntitlement;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Orders & Sales List.
     */
    public function index(Request $request)
    {
        $query = Order::with(['items', 'user'])->latest();

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_email', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('tenant.admin.finance.orders.index', compact('orders'));
    }

    /**
     * Show Order Details.
     */
    public function show($id)
    {
        $order = Order::with(['items.purchasable', 'items.variant', 'transactions', 'user'])->findOrFail($id);
        return view('tenant.admin.finance.orders.show', compact('order'));
    }

    /**
     * Approve manual bank payment proof.
     */
    public function approvePayment($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $order->update([
            'payment_status' => 'paid',
        ]);

        Transaction::where('order_id', $order->id)->update([
            'status' => 'success',
        ]);

        // Grant entitlements & course access upon admin approval
        \App\Services\Commerce\CheckoutService::fulfillOrder($order);

        return back()->with('success', "Payment for Order #{$order->order_number} has been approved and marked as Paid!");
    }

    /**
     * Update order fulfillment status.
     */
    public function updateFulfillment(Request $request, $id)
    {
        $request->validate([
            'fulfillment_status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'fulfillment_status' => $request->fulfillment_status,
        ]);

        return back()->with('success', "Order #{$order->order_number} fulfillment status updated to {$request->fulfillment_status}!");
    }
}
