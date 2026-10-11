<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArzaqPaymentService
{
    protected string $paymentUrl;
    protected ?string $secretKey;

    public function __construct()
    {
        $this->paymentUrl = (string) config('services.arzaq.payment_url', env('ARZAQ_PAYMENT_URL', 'https://payments.arzaqinsights.test'));
        $this->secretKey = config('services.arzaq.payment_secret', config('services.arzaq.client_secret', config('app.key')));
    }

    /**
     * Get the base Arzaq Payment platform URL.
     */
    public function getPaymentUrl(): string
    {
        return rtrim($this->paymentUrl, '/');
    }

    /**
     * Get the API endpoint to initiate a checkout session.
     */
    public function getCheckoutSessionEndpoint(): string
    {
        return $this->getPaymentUrl() . '/api/v1/checkout/session';
    }

    /**
     * Create a checkout session on the Arzaq Central Payment Engine.
     */
    public function createCheckoutSession(array $payload): array
    {
        $endpoint = $this->getCheckoutSessionEndpoint();

        try {
            Log::info('Initiating Arzaq Central Payment Session', [
                'endpoint' => $endpoint,
                'amount'   => $payload['amount'] ?? null,
                'purpose'  => $payload['purpose'] ?? null,
                'email'    => $payload['customer_email'] ?? null,
            ]);

            $response = Http::timeout(10)
                ->withoutVerifying()
                ->withHeaders([
                    'X-Platform-Client' => config('services.arzaq.client_id', 'arzavo'),
                    'X-Platform-Secret' => $this->secretKey,
                    'Accept'            => 'application/json',
                ])
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Arzaq Checkout Session Created', [
                    'session_id'   => $data['session_id'] ?? null,
                    'checkout_url' => $data['checkout_url'] ?? null,
                ]);

                return array_merge(['success' => true], $data);
            }

            Log::error('Arzaq Central Checkout Session Failed', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Payment service failed to create checkout session.',
                'error'   => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Arzaq Payment Service Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to connect to Central Payment Engine: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch real-time status of a checkout session.
     */
    public function getSessionStatus(string $sessionId): array
    {
        $url = $this->getPaymentUrl() . '/status/' . $sessionId;

        try {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            return ['status' => 'unknown', 'error' => $response->body()];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
