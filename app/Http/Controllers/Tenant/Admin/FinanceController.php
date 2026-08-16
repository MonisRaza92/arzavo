<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Transaction;
use App\Services\Commerce\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * 1. Orders & Sales List.
     */
    public function index(Request $request)
    {
        $query = Order::with(['items', 'user'])->latest();

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('gateway')) {
            $query->where('payment_gateway', $request->gateway);
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

        $stats = [
            'total_sales' => Order::where('payment_status', 'paid')->sum('grand_total'),
            'paid_count' => Order::where('payment_status', 'paid')->count(),
            'pending_count' => Order::whereIn('payment_status', ['verification_pending', 'unpaid'])->count(),
            'total_orders' => Order::count(),
        ];

        return view('tenant.admin.finance.orders.index', compact('orders', 'stats'));
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
        CheckoutService::fulfillOrder($order);

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

    /**
     * 2. Invoices List.
     */
    public function invoices(Request $request)
    {
        $query = Order::with(['items', 'user'])->where('payment_status', 'paid')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_email', 'like', "%{$s}%");
            });
        }

        $invoices = $query->paginate(20)->withQueryString();

        $totalInvoiced = Order::where('payment_status', 'paid')->sum('grand_total');

        return view('tenant.admin.finance.invoices.index', compact('invoices', 'totalInvoiced'));
    }

    /**
     * View / Print Individual Invoice.
     */
    public function invoiceShow($id)
    {
        $order = Order::with(['items', 'transactions', 'user'])->findOrFail($id);
        return view('tenant.admin.finance.invoices.show', compact('order'));
    }

    /**
     * 3. Financial Reports & Analytics.
     */
    public function reports(Request $request)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $stats = [
            'today_sales' => Order::where('payment_status', 'paid')->whereDate('created_at', $today)->sum('grand_total'),
            'month_sales' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisMonth)->sum('grand_total'),
            'year_sales' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisYear)->sum('grand_total'),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('grand_total'),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'pending_orders' => Order::whereIn('payment_status', ['verification_pending', 'unpaid'])->count(),
        ];

        // Sales Breakdown by Gateway
        $gatewayBreakdown = Order::select('payment_gateway', DB::raw('count(*) as count'), DB::raw('sum(grand_total) as total'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_gateway')
            ->get();

        // Top Purchased Items
        $topItems = OrderItem::select('item_name', 'purchasable_type', DB::raw('sum(quantity) as total_qty'), DB::raw('sum(total_price) as total_amount'))
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })
            ->groupBy('item_name', 'purchasable_type')
            ->orderByDesc('total_amount')
            ->take(10)
            ->get();

        // Recent 10 Transactions
        $recentTransactions = Transaction::with('order')->latest()->take(10)->get();

        return view('tenant.admin.finance.reports.index', compact('stats', 'gatewayBreakdown', 'topItems', 'recentTransactions'));
    }

    /**
     * 4. Student Fee Payments & Collection Ledger.
     */
    public function fees(Request $request)
    {
        $query = \App\Models\Tenant\FeePayments::with(['student.class', 'student.subject', 'feePlan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('transaction_id', 'like', "%{$s}%")
                  ->orWhereHas('student', function ($sq) use ($s) {
                      $sq->where('fname', 'like', "%{$s}%")
                         ->orWhere('lname', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        $feePayments = $query->paginate(20)->withQueryString();

        $students = \App\Models\Tenant\User::where('role', 'student')->with(['class', 'feePlans', 'feePayments'])->orderBy('fname')->get();
        $classes = \App\Models\Tenant\ClassCourse::orderBy('name')->get();

        $totalInvoiced = \App\Models\Tenant\FeePlans::sum('amount');
        $totalCollected = \App\Models\Tenant\FeePayments::where('status', 'paid')->sum('amount_paid');
        $pendingDues = max(0, $totalInvoiced - $totalCollected);

        $stats = [
            'total_invoiced' => $totalInvoiced,
            'total_collected' => $totalCollected,
            'pending_dues' => $pendingDues,
            'paid_count' => \App\Models\Tenant\FeePayments::where('status', 'paid')->count(),
            'pending_count' => \App\Models\Tenant\FeePayments::where('status', 'pending')->count(),
            'total_students' => $students->count(),
        ];

        return view('tenant.admin.finance.fees.index', compact('feePayments', 'students', 'classes', 'stats'));
    }

    /**
     * Record a Fee Payment (Manual, Cash, Bank, UPI).
     */
    public function recordFeePayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $feePlan = \App\Models\Tenant\FeePlans::where('student_id', $request->student_id)->first();

        \App\Models\Tenant\FeePayments::create([
            'student_id' => $request->student_id,
            'fee_plan_id' => $feePlan ? $feePlan->id : null,
            'amount' => $request->amount_paid,
            'amount_paid' => $request->amount_paid,
            'final_amount' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'payment_type' => 'manual',
            'transaction_id' => $request->transaction_id ?: ('FEE-' . rand(100000, 999999)),
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Fee payment recorded successfully.');
    }

    /**
     * Mark or Update Fee Payment Status (e.g. from Pending to Paid with method).
     */
    public function updateFeeStatus(Request $request, $id)
    {
        $payment = \App\Models\Tenant\FeePayments::findOrFail($id);

        $request->validate([
            'status' => 'required|in:paid,pending,overdue',
            'payment_method' => 'nullable|string',
        ]);

        $payment->status = $request->status;
        if ($request->filled('payment_method')) {
            $payment->payment_method = $request->payment_method;
        }
        if ($request->status === 'paid' && empty($payment->payment_date)) {
            $payment->payment_date = now()->toDateString();
        }
        $payment->save();

        return redirect()->back()->with('success', 'Fee payment status updated successfully.');
    }

    /**
     * Delete Fee Payment Record.
     */
    public function deleteFeePayment($id)
    {
        $payment = \App\Models\Tenant\FeePayments::findOrFail($id);
        $payment->delete();

        return redirect()->back()->with('success', 'Fee payment record deleted.');
    }
}
