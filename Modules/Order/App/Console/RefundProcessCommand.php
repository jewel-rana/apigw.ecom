<?php

namespace Modules\Order\App\Console;

use Illuminate\Console\Command;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Services\RefundService;
use Modules\Payment\App\Constants\PaymentRefundConstant;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RefundProcessCommand extends Command
{
    protected $signature = 'refund:process';

    protected $description = 'Refund process command.';

    public function handle(): void
    {
        try {
            $this->info('REFUND PROCESS STARTED');
            $refunds = Refund::where('status', PaymentRefundConstant::REFUND_STATUS_PROCESSING)
                ->where('attempts', '<=', 2)
                ->limit(5)
                ->get();
            foreach ($refunds as $refund) {
                $this->info('REFUND PROCESSING ' . $refund->id);
                app(RefundService::class)->process($refund);
                $this->info('REFUND PROCESSED ' . $refund->id);
            }
            $this->info('REFUND PROCESS COMPLETED');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
