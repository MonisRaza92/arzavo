<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opening Razorpay Checkout...</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .pay-box {
            text-align: center;
            padding: 2.5rem;
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            max-width: 420px;
            width: 90%;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1.5rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h2 { font-size: 1.35rem; font-weight: 800; margin: 0 0 0.5rem; color: #0f172a; }
        p { font-size: 0.875rem; color: #64748b; margin: 0 0 1.5rem; }
        .retry-btn {
            background-color: #3b82f6;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            border-radius: 0.75rem;
            cursor: pointer;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="pay-box">
        <div class="spinner"></div>
        <h2>Opening Razorpay Gateway</h2>
        <p>Please complete payment in the secure popup...</p>

        <button type="button" id="retry_btn" class="retry-btn" onclick="openRazorpay()">
            Click to Open Razorpay
        </button>

        <form id="razorpay_verify_form" method="POST" action="{{ route('checkout.razorpay.verify') }}" style="display: none;">
            @csrf
            <input type="hidden" name="order_number" value="{{ $order->order_number }}">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>
    </div>

    <script>
        function openRazorpay() {
            var options = {
                "key": "{{ $payment['key'] }}",
                "amount": "{{ $payment['amount'] }}",
                "currency": "{{ $payment['currency'] ?? 'INR' }}",
                "name": "{{ app('currentTenant')->name ?? 'Academy' }}",
                "description": "Payment for Order #{{ $order->order_number }}",
                "prefill": {
                    "name": "{{ $order->customer_name }}",
                    "email": "{{ $order->customer_email }}",
                    "contact": "{{ $order->customer_phone }}"
                },
                "theme": {
                    "color": "#4f46e5"
                },
                "handler": function (response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id || '';
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id || '';
                    document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
                    document.getElementById('razorpay_verify_form').submit();
                },
                "modal": {
                    "ondismiss": function() {
                        window.location.href = "{{ route('checkout.show') }}?order_id={{ $order->order_number }}&status=cancelled";
                    }
                }
            };

            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function (response) {
                alert("Payment Failed: " + (response.error.description || 'Unknown error'));
            });
            rzp.open();
        }

        document.addEventListener('DOMContentLoaded', function() {
            openRazorpay();
        });
    </script>
</body>
</html>
