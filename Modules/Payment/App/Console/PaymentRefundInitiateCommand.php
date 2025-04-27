<?php

namespace Modules\Payment\App\Console;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Modules\Order\App\Models\Refund;
use Modules\Payment\App\Constants\PaymentRefundConstant;

class PaymentRefundInitiateCommand extends Command
{
    protected $signature = 'refund:initiate';

    protected $description = 'Payment refund command.';

    public function handle(): void
    {
        try {
            Refund::where('status', PaymentRefundConstant::REFUND_STATUS_PROCESSING)
                ->where('attempts', '<=', 2)
                ->cursor()
                ->each(function ($refund) {
                    $refund->increment('attempts');
                    $gateway = CommonHelper::purseGateway($refund->gateway);
                    $data = ['status' => false];
                    (new $gateway)->refund($refund, $data);
                });
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            LogHelper::exception($exception, [
                'keyword' => 'payment:refund'
            ]);
        }
    }
}
