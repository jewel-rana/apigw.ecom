<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Models\Order;
use App\Models\Promotion;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function form(Request $request)
    {
        try {
            $promotions = Promotion::get()
                ->map(function (Promotion $promotion) {
                    return $promotion->format();
                });
            return response()->success($promotions);
        } catch (\Exception $exception) {
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function create(array $data)
    {
        try {
            $order = $this->orderRepository->create($data);
            $order->attributes()->saveMany($data['objectives']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function getOrders(Request $request)
    {
        try {
            $orders = Order::filter($request)
                ->paginate($request->integer('per_page', 10));
            return response()->success(CommonHelper::parsePaginator($orders));
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_LIST_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }
}
