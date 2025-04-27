<?php
namespace Modules\Payment\App\Constants;

class PaymentRefundConstant {
    const REFUND_STATUS_PENDING         = 'pending';
    const REFUND_STATUS_PROCESSING      = 'processing';
    const REFUND_STATUS_SUCCESS         = 'success';
    const REFUND_STATUS_FAILED          = 'failed';
    const REFUND_STATUS_INITIATED       = 'initiated';
    const REFUNDED                      = 'refunded';
}
