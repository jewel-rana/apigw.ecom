<?php

namespace Modules\Order\App\Console;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Processor\Kartat;
use Illuminate\Console\Command;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Helpers\OrderHelper;
use Modules\Order\App\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Payment\App\Constants\PaymentConstant;

class OrderPurchaseVerifyCommand extends Command
{
    protected $signature = 'order:verify';

    protected $description = 'Order purchase verify command';
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
                ->whereIn('status', [OrderConstant::IN_PROGRESS, OrderConstant::UNSTABLE])
                ->has('payment')
                ->cursor()
                ->each(function ($order, $k) {
                    $this->info("Order processing : {$order->id} : {$order->payments->count()}");
                    foreach($order->items as $item) {
                        if($item->status != OrderItemConstant::SUCCESS) {
                            $data = ['status' => false];
                            (new Kartat())->verify($item, $data);
                        }
                    }

                    if (!$order->items->where('status', OrderItemConstant::PENDING)->count()) {
                        $order->refresh();
                        OrderHelper::updateOrderStatus($order);
                    }
                });
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_VERIFY_COMMAND_EXCEPTION'
            ]);
        }
    }
}
