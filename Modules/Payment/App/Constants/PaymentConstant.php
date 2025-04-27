<?php

namespace Modules\Payment\App\Constants;

class PaymentConstant
{
    const INIT              = 'init';
    const CREATE            = 'create';
    const EXECUTE           = 'execute';
    const VERIFY            = 'verify';
    const IPN               = 'ipn';
    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS    = 'success';
    const STATUS_DECLINED   = 'declined';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_FAILED     = 'failed';
    const PROCESSING_PERIOD = 3;
    const PAYMENT_VERIFICATION_PERIOD = 30;
    const REFUND            = 'refund';
    const REFUNDED          = 'refunded';
    const STATUS_INITIATED  = 'initiated';
}
