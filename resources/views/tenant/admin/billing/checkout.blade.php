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
        document.addEventListener("turbo:load", () => {

            const btn = document.getElementById("pay-btn");

            if (!btn) return; // 🔥 IMPORTANT

            const cashfree = Cashfree({ mode: "sandbox" });

            document.addEventListener("click", async (e) => {

                if (!e.target.matches("#pay-btn")) return;

                const btn = e.target;

                btn.disabled = true;
                btn.innerText = "Processing...";

                try {
                    const res = await fetch("{{ route('admin.payment.session', $plan->id) }}");

                    if (!res.ok) {
                        const text = await res.text();
                        console.error("Server Error:", text);
                        throw new Error("Server failed");
                    }

                    const data = await res.json();

                    if (!data.payment_session_id) {
                        throw new Error("Session failed");
                    }

                    const cashfree = Cashfree({ mode: "sandbox" });

                    cashfree.checkout({
                        paymentSessionId: data.payment_session_id,
                        redirectTarget: "_self"
                    });

                } catch (err) {
                    console.error(err);
                    alert("Payment failed");
                    btn.disabled = false;
                    btn.innerText = "Pay Now";
                }

            });
        });
    </script>
@endsection