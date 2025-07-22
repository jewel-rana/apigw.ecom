<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Order\App\Services\OrderService;
use Modules\Order\Http\Requests\Api\OrderCheckoutRequest;

class OrderCheckoutController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(OrderCheckoutRequest $request)
    {
        return $this->orderService->create($request);
    }
}
