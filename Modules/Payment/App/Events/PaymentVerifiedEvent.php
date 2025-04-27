<?php

namespace Modules\Payment\App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Payment\App\Models\Payment;

class PaymentVerifiedEvent
{
    use SerializesModels;
    public Payment $payment;
    public array $data;
    public function __construct(Payment $payment, array $data)
    {
        $this->payment = $payment;
        $this->data = $data;
    }
}
