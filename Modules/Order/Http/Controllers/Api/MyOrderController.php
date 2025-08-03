<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Services\OrderService;

class MyOrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        return $this->orderService->getIndex($request);
    }

    public function show(Order $order)
    {
        if ($order->isNotOwner()) {
            return response()->failed(['message' => __('Sorry! you are not the owner of the property.')]);
        }

        return response()->success(
//            CommonHelper::orderMessage($order->payment) +
            $order->format(true)
        );
    }
}
