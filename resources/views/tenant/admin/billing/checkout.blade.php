@extends('layouts.admin')

@section('content')
    <div class="text-center space-y-4 flex flex-col pt-30 items-center justify-center">

        <h2 class="text-xl font-semibold">
            {{ $plan->name }}
        </h2>

        <p class="text-2xl font-bold">
            ₹{{ $plan->monthly_price }}
        </p>

        <button id="pay-btn" class="bg-invert text-invert px-6 py-2 border-rounded">
            Pay Now
        </button>

    </div>

    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <script>
        (function () {

            function initPayment() {
                const btn = document.getElementById("pay-btn");

                if (!btn) return;

                // ❌ duplicate binding rokne ke liye
                if (btn.dataset.bound === "true") return;
                btn.dataset.bound = "true";

                const cashfree = Cashfree({ mode: "sandbox" });

                btn.addEventListener("click", async () => {

                    btn.disabled = true;
                    btn.innerText = "Processing...";

                    try {
                        const res = await fetch("{{ route('admin.payment.session', $plan->id) }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        });

                        const data = await res.json();

                        console.log("Payment Response:", data);

                        if (!data.payment_session_id || data.payment_session_id.length < 10) {
                            throw new Error("Invalid payment session");
                        }

                        await cashfree.checkout({
                            paymentSessionId: data.payment_session_id,
                            redirectTarget: "_self"
                        });

                    } catch (err) {
                        console.error("Payment Error:", err);

                        alert("Payment failed. Try again.");

                        btn.disabled = false;
                        btn.innerText = "Pay Now";
                    }
                });
            }

            // ✅ Normal page load
            document.addEventListener("DOMContentLoaded", initPayment);

            // ✅ Turbo navigation
            document.addEventListener("turbo:load", initPayment);

        })();
    </script>

@endsection