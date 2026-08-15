<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Secure Payment Gateway...</title>
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
        .redirect-box {
            text-align: center;
            padding: 2.5rem;
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            max-width: 400px;
            width: 90%;
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top-color: #4f46e5;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1.5rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h2 { font-size: 1.25rem; font-weight: 700; margin: 0 0 0.5rem; }
        p { font-size: 0.875rem; color: #64748b; margin: 0; }
    </style>
</head>
<body>
    <div class="redirect-box">
        <div class="spinner"></div>
        <h2>Redirecting to Payment Gateway</h2>
        <p>Please wait while we transfer you securely...</p>

        <form id="payment_auto_form" method="POST" action="{{ $action }}">
            @foreach($params as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <noscript>
                <button type="submit" style="margin-top: 1rem; padding: 0.5rem 1rem;">Click here to proceed</button>
            </noscript>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('payment_auto_form').submit();
        });
    </script>
</body>
</html>
