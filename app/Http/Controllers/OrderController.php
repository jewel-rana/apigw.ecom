<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Http\Requests\OrderActionRequest;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        return $this->orderService->getOrders($request);
    }

    public function create(Request $request)
    {
        return $this->orderService->form($request);
    }

    public function store(OrderCreateRequest $request)
    {
        return $this->orderService->create($request->validated());
    }

    public function show(Order $order)
    {
        return response()->success($order->format());
    }

    public function update(OrderUpdateRequest $request, int $id)
    {
        return $this->orderService->update($request->validated(), $id);
    }

    public function action(OrderActionRequest $request, Order $order)
    {
        return $this->orderService->action($request, $order);
    }

    public function export(Request $request): BinaryFileResponse
    {
        return (new OrderExport($request))->download('order.xlsx');
    }

    public function callAction($method, $parameters)
    {
        if(!in_array($method, ['export', 'action'])) {
            $this->authorize($method, Order::class);
        }

        return parent::callAction($method, $parameters);
    }
}
