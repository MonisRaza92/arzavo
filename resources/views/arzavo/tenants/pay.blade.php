<button id="pay-btn">Pay Now</button>

<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        // ✅ ensure SDK loaded
        if (typeof Cashfree === "undefined") {
            alert("Cashfree SDK failed to load");
            return;
        }

        const cashfree = Cashfree({
            mode: "sandbox"
        });

        document.getElementById("pay-btn").addEventListener("click", async () => {

            const btn = document.getElementById("pay-btn");
            btn.disabled = true;
            btn.innerText = "Processing...";

            try {
                const res = await fetch("/pay/49");
                const data = await res.json();

                console.log(data);

                if (!data.payment_session_id) {
                    alert("Payment session not created");
                    btn.disabled = false;
                    btn.innerText = "Pay Now";
                    return;
                }

                cashfree.checkout({
                    paymentSessionId: data.payment_session_id,
                    redirectTarget: "_self"
                });

            } catch (err) {
                console.error(err);
                alert("Something went wrong");
                btn.disabled = false;
                btn.innerText = "Pay Now";
            }

        });
    });
</script>