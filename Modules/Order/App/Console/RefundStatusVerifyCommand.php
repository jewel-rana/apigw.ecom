<?php

namespace Modules\Order\App\Console;

use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Services\RefundService;
use Modules\Payment\App\Constants\PaymentRefundConstant;

class RefundStatusVerifyCommand extends Command
{
    protected $signature = 'app:refund-status-verify';
    protected $description = 'Refund Status Verify with Payment Gateway';

    public function handle()
    {
        try {
            $this->info('Refund Status Verify Start');
            Refund::where('status', PaymentRefundConstant::REFUND_STATUS_INITIATED)
                ->where('order_id', '>', 279691)
                ->limit(50)
                ->cursor()
                ->each(function ($refund) {
                    $this->info('REFUND PROCESSING ' . $refund->id);
                    app(RefundService::class)->verify($refund);
                    $this->info('REFUND PROCESSED ' . $refund->id);
                });
            $this->info('Refund Status Verify End');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            LogHelper::exception($exception, [
                'keyword' => 'REFUND_STATUS_VERIFY_COMMAND_EXCEPTION',
            ]);
        }
    }
}
