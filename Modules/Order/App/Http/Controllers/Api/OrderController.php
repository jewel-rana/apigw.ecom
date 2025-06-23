<?php

namespace Modules\Order\App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Order\App\Http\Requests\Api\StoreOrderRequest;
use Modules\Order\App\Http\Requests\OrderDeliveryRequest;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderItem;
use Modules\Order\App\Services\OrderService;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        return $this->orderService->getMyOrders($request, auth()->id());
    }

    public function store(StoreOrderRequest $request)
    {
        return $this->orderService->create($request);
    }

    public function show(Order $order)
    {
        if ($order->isNotOwner()) {
            return response()->failed(['message' => __('Sorry! you are not the owner of the property.')]);
        }
        return response()->success(
            CommonHelper::orderMessage($order->payment) +
            $order->format(true)
        );
    }

    public function payload(OrderItem $item)
    {
        return $this->orderService->getPayload($item);
    }

    public function deliver(Order $order, OrderDeliveryRequest $request): array
    {
        return $this->orderService->deliver($order, $request);
    }
}
