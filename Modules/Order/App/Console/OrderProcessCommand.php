<?php

namespace Modules\Order\App\Console;

use App\Helpers\LogHelper;
use App\Processor\Kartat;
use Illuminate\Console\Command;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Helpers\OrderHelper;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderItem;
use Modules\Order\App\Notifications\OrderDeliveryNotification;
use Modules\Order\App\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\App\Services\RefundService;
use Modules\Payment\App\Constants\PaymentConstant;

class OrderProcessCommand extends Command
{
    protected $signature = 'order:process';

    protected $description = 'Processing order for customer after successful payment';
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
    }

    public function handle(): void
    {
        try {
            $this->info("ORDER PROCESSING COMMAND STARTED");
            LogHelper::debug("ORDER PROCESSING COMMAND STARTED");
            $this->orderRepository->getModel()
                ->with('payment')
                ->where('created_at', '>=', now()->subMinutes(OrderConstant::PROCESSING_PERIOD))
                ->where('status', OrderConstant::PROCESSING)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentConstant::STATUS_SUCCESS);
                })
                ->orderBy('created_at')
                ->cursor()
                ->each(function (Order $order, $k) {
                    if($order->is_refund_initiated) {
                        $order->update(['status' => OrderConstant::FAILED]);
                        $order->items->each(function (OrderItem $orderItem) {
                            $orderItem->update(['status' => OrderItemConstant::FAILED]);
                        });
                    } else {
                        LogHelper::debug("ORDER PROCESSING COMMAND STARTED " . $order->id);
                        $this->info("ORDER PROCESSING ORDER FOUND: " . $order->id);
                        if ($order->payment && $order->payment->status == PaymentConstant::STATUS_SUCCESS) {
                            foreach ($order->items as $item) {
                                if ($item->status == OrderConstant::PENDING) {
                                    $this->info("ORDER PROCESSING ITEM FOUND");
                                    LogHelper::debug("PROCESSING ORDER ITEM #{$item->order_id} - {$item->id}");
                                    $this->info("PROCESSING ORDER ITEM #{$item->order_id} - {$item->id}");
                                    $data = ['status' => false, 'message' => __('Failed')];
                                    $data = (new Kartat())->execute($item, $data);

                                    if(!app()->environment(['production'])) {
                                        LogHelper::debug($data, [
                                            'keyword' => 'PURCHASE_PROCESS_RESPONSE'
                                        ]);
                                    }

                                    if ($data['state'] == 'SUCCESS' && array_key_exists('data', $data)) {
                                        if (
                                            array_key_exists('vouchers', $data['data'])
                                            && count($data['data']['vouchers'])
                                            && !config('socialite.fib.enabled')
                                        ) {
                                            $vouchers = $data['data']['vouchers'];
                                            $order->customer->notify(new OrderDeliveryNotification($order, $item, $vouchers));
                                        }
                                    }
                                }
                            }

                            //update order status
                            $order->refresh();
                            if (!$order->items->where('status', OrderItemConstant::PENDING)->count()) {
                                OrderHelper::updateOrderStatus($order);
                            }
                        } else {
                            if (
                                in_array($order->payment->status,
                                    [
                                        PaymentConstant::STATUS_FAILED,
                                        PaymentConstant::STATUS_DECLINED,
                                        PaymentConstant::STATUS_CANCELLED
                                    ]
                                ) || $order->payment->paymentProcessingPeriodExpired()
                            ) {
                                $order->update(['status' => OrderConstant::FAILED]);
                                $order->items->each(function ($item) {
                                    $item->update(['status' => OrderItemConstant::FAILED]);
                                });
                            }
                        }
                    }
                });

            $this->info("ORDER PROCESSING COMMAND ENDED");
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_VERIFY_COMMAND_EXCEPTION'
            ]);
        }
    }
}
