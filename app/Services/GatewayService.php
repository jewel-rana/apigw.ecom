<?php

namespace App\Services;

use App\Gateway\GatewayInterface;
use App\Models\Payment;

class GatewayService
{
    public function token(GatewayInterface $gateway, object $order)
    {
        return $gateway->token($order);
    }

    public function create(GatewayInterface $gateway, Payment $payment)
    {
        return $gateway->create($payment);
    }

    public function execute(GatewayInterface $gateway, Payment $payment)
    {
        return $gateway->execute($payment);
    }

    public function verify(GatewayInterface $gateway, $payment)
    {
        return $gateway->verify($payment);
    }

    public function intend(GatewayInterface $gateway)
    {
        // TODO: Implement intend() method.
    }
}
