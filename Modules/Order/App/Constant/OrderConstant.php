<?php

namespace Modules\Order\App\Constant;

class OrderConstant
{
    const PENDING           = 'pending';
    const PROCESSING        = 'processing';
    const IN_PROGRESS       = 'in-progress';
    const COMPLETE          = 'complete';
    const CANCELLED         = 'cancelled';
    const DECLINED          = 'declined';
    const FAILED            = 'failed';
    const PROCESSING_PERIOD = 10;
    const MAX_DELIVERY_ATTEMPTS = 3;
    const UNSTABLE          = 'unstable';
    const PARTIAL           = 'partial';
}
