<?php

namespace Modules\Order\App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Models\Order;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class OrderNonePaymentStatusUpdateCommand extends Command
{
    protected $signature = 'app:order-none-payment-status-update';

    protected $description = 'Command description.';

    public function handle(): void
    {
        try {
            DB::beginTransaction();
            Order::where('created_at', '<', now()->subMinutes(OrderConstant::PROCESSING_PERIOD + 10))
                ->where('status', OrderConstant::PENDING)
                ->doesntHave('payment')
                ->cursor()
                ->each(function (Order $order) {
                $this->info("None payment status updated. {$order->id}");
                $order->update(['status' => OrderConstant::FAILED, 'remarks' => 'No payment made yet']);
                $order->items()->update(['status' => OrderItemConstant::FAILED]);
            });
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->error($exception->getMessage());
        }
    }
}
