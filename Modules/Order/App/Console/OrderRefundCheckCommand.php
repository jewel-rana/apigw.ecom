<?php

namespace Modules\Order\App\Console;

use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Services\RefundService;
use Modules\Payment\App\Constants\PaymentConstant;

class OrderRefundCheckCommand extends Command
{
    protected $signature = 'order:refund-check';

    protected $description = 'Order refund check command.';

    public function handle(): void
    {
        try {
            $this->info('ORDER REFUND CHECK STARTED');
            $orders = Order::where('status', OrderConstant::FAILED)
                ->where('created_at', '<=', now()->subMinutes(OrderConstant::PROCESSING_PERIOD + 5))
                ->where('is_refund_initiated', false)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentConstant::STATUS_SUCCESS);
                })
                ->cursor();
            foreach ($orders as $order) {
                $this->info('ORDER REFUND CHECK PROCESSING ' . $order->id);
                app(RefundService::class)->create($order);
                $this->info('ORDER REFUND CHECK PROCESSING ' . $order->id);
            }
            $this->info('ORDER REFUND CHECK COMPLETED');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            LogHelper::exception($exception, [
                'keyword' => 'order:refund'
            ]);
        }
    }
}
