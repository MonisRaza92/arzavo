<?php

namespace App\Services\Payment;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Services\Payment\Drivers\RazorpayDriver;
use App\Services\Payment\Drivers\CashfreeDriver;
use App\Services\Payment\Drivers\PayuDriver;
use App\Services\Payment\Drivers\PaytmDriver;
use App\Services\Payment\Drivers\CodDriver;
use App\Services\Payment\Drivers\ManualBankDriver;
use InvalidArgumentException;

class PaymentManager
{
    protected array $drivers = [];

    public function __construct()
    {
        $this->registerDriver('razorpay', RazorpayDriver::class);
        $this->registerDriver('cashfree', CashfreeDriver::class);
        $this->registerDriver('payu', PayuDriver::class);
        $this->registerDriver('paytm', PaytmDriver::class);
        $this->registerDriver('cod', CodDriver::class);
        $this->registerDriver('manual_bank', ManualBankDriver::class);
    }

    /**
     * Register a payment driver dynamically.
     */
    public function registerDriver(string $name, string $driverClass): void
    {
        $this->drivers[$name] = $driverClass;
    }

    /**
     * Resolve driver instance by name.
     */
    public function driver(string $name): PaymentDriverInterface
    {
        if (!isset($this->drivers[$name])) {
            throw new InvalidArgumentException("Unsupported payment driver [{$name}].");
        }

        return app($this->drivers[$name]);
    }
}
