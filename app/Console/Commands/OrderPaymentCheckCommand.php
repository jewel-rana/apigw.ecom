<?php

namespace App\Console\Commands;

use App\Constants\AppConstant;
use App\Models\Order;
use Illuminate\Console\Command;

class OrderPaymentCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:check-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::where('created_at', '<=', now()->subHour())
            ->where('status', AppConstant::ORDER_PENDING)
            ->get()
            ->each(function (Order $order) {
                if(!$order->payment || $order->payment->status != AppConstant::PAYMENT_SUCCESS) {
                    $order->update(['status' => AppConstant::ORDER_INACTIVE, 'remarks' => 'Payment failed']);
                }
            });
    }
}
