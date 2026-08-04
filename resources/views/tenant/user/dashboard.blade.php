@extends('layouts.user')
@section('title', 'User Dashboard - Customer Portal')

@section('content')
    <!-- WELCOME BANNER CARD (MOBILE FIRST) -->
    <div class="mb-4 p-4 sm:p-6 border-rounded bg-primary border-primary shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="px-2.5 py-0.5 rounded text-[10px] bg-blue-500/10 text-blue-600 font-bold border border-blue-500/20 uppercase tracking-wider">
                    Customer Account
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-primary tracking-tight mt-1.5 flex items-center gap-2">
                    Welcome back, {{ $user->fname ?? 'Customer' }}! 👋
                </h1>
                <p class="text-xs text-secondary mt-1">
                    Manage your orders, downloadable study materials, and billing invoices.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('user.orders') }}" class="w-full sm:w-auto px-4 py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center">
                    View Orders
                </a>
            </div>
        </div>
    </div>

    <!-- STATS CARDS GRID (MOBILE FIRST 1 COL -> 2 COL -> 4 COL) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL PURCHASES</span>
                <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-600 flex items-center justify-center text-sm border border-blue-500/20">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $totalOrdersCount }}</div>
            <p class="text-[11px] text-secondary">Completed order transactions</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL SPENT</span>
                <div class="w-8 h-8 rounded bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm border border-emerald-500/20">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">₹{{ number_format($totalSpent, 2) }}</div>
            <p class="text-[11px] text-secondary">Total amount paid</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">DIGITAL BOOKS</span>
                <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-600 flex items-center justify-center text-sm border border-purple-500/20">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $orders->where('payment_status', 'paid')->count() }}</div>
            <p class="text-[11px] text-secondary">Accessible E-books & PDFs</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">MY INQUIRIES</span>
                <div class="w-8 h-8 rounded bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm border border-amber-500/20">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $inquiriesCount }}</div>
            <p class="text-[11px] text-secondary">Support messages sent</p>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE (MOBILE SCROLL) -->
    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs mb-6">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-clock-history text-indigo-500"></i> Recent Orders
            </h3>
            <a href="{{ route('user.orders') }}" class="text-xs font-bold text-blue-600 hover:underline">View All &rarr;</a>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Order ID</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Total Amount</th>
                            <th class="py-2.5 px-3">Payment Status</th>
                            <th class="py-2.5 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($orders->take(5) as $order)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">#{{ $order->order_number ?? 'ORD-' . $order->id }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-primary">₹{{ number_format($order->grand_total, 2) }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border-amber-500/20' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('user.invoices') }}" class="px-2.5 py-1 bg-hover-secondary text-primary border-primary border-rounded font-semibold text-[11px] hover-primary transition">
                                        Invoice
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-tertiary text-xs border-dashed border-rounded">
                No recent orders found. Browse store to purchase books or study material.
            </div>
        @endif
    </div>
@endsection
