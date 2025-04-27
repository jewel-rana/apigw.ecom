<?php

namespace Modules\Order\App\Console;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Payment\App\Constants\PaymentConstant;

class OrderPaymentVerifyCommand extends Command
{
    protected $signature = 'order:payment-verify';

    protected $description = 'Order payment verify command';
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
    }

    public function handle(): void
    {
        try {
            $this->orderRepository->with(['payments'])
                ->where('created_at', '<=', now()->subMinutes(OrderConstant::PROCESSING_PERIOD))
                ->whereIn('status', [OrderConstant::IN_PROGRESS, OrderConstant::UNSTABLE])
                ->has('payment')
                ->cursor()
                ->each(function ($order, $k) {
                    $this->info("Order processing : {$order->id} : {$order->payments->count()}");

                    $pendingPayments = $order->payments->whereIn('status', [
                        PaymentConstant::STATUS_PENDING,
                        PaymentConstant::STATUS_PROCESSING
                    ]);

                    $this->info("Order processing pending payment : {$order->id} : {$pendingPayments->count()}");
                    if ($pendingPayments->count()) {
                        foreach ($pendingPayments as $payment) {
                            $gateway = CommonHelper::purseGateway($payment?->gateway);
                            $data = ['status' => false];
                            $data = (new $gateway)->verify($payment, $data);
                        }
                    }

                    $order->refresh();

                    //Need to deploy this code after testing
                    if (
                        !$order->payments->where('status', PaymentConstant::STATUS_SUCCESS)->count()
                        && $order->payments->where('status', PaymentConstant::STATUS_FAILED)->count()
                    ) {
                        $order->update(['status' => OrderConstant::FAILED, 'remarks' => 'Payment failed!']);
                        $order->items->update(['status' => OrderItemConstant::FAILED]);
                    }
                });
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_VERIFY_COMMAND_EXCEPTION'
            ]);
        }
    }
}
