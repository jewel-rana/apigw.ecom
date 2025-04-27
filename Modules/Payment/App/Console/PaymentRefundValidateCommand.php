<?php

namespace Modules\Payment\App\Console;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Modules\Order\App\Models\Refund;
use Modules\Payment\App\Constants\PaymentRefundConstant;

class PaymentRefundValidateCommand extends Command
{
    protected $signature = 'refund:validate';

    protected $description = 'Payment Refund Verify Command.';

    public function handle(): void
    {
        try {
            Refund::where('status', PaymentRefundConstant::REFUND_STATUS_PENDING)
                ->cursor()
                ->each(function ($refund) {
                    if($refund->order->payment) {
                        $gateway = CommonHelper::purseGateway($refund->gateway);
                        $data = ['status' => false];
                        $data = (new $gateway)->verifyRefund($refund->order?->payment, $data);
                        if ($data['status']) {
                            $this->info("Refund {$refund->order->id} successfully verified.");
                        } else {
                            $this->error("Refund {$refund->order->id} is not processed.");
                            $refund->attempts++;
                            if ($refund->attempts >= 3) {
                                $refund->status = PaymentRefundConstant::REFUND_STATUS_FAILED;
                                $refund->remarks = 'Payment failed in verification';
                            }
                        }
                        $refund->save();
                    }
                });
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            LogHelper::exception($exception, [
                'keyword' => 'refund:validate',
            ]);
        }
    }
}
