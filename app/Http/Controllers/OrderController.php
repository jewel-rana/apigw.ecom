<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, int $id)
    {
        return $this->orderService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export(Request $request): BinaryFileResponse
    {
        return (new OrderExport($request))->download('order.xlsx');
    }

    public function callAction($method, $parameters)
    {
        if($this->authorize($method, Order::class)) {
            return parent::callAction($method, $parameters);
        }
    }
}
