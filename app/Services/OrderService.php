<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Http\Requests\OrderActionRequest;
use App\Models\Order;
use App\Models\OrderAttribute;
use App\Models\Promotion;
use App\Notifications\OrderUpdateNotification;
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
            $order = $this->orderRepository->create($data + [
                    'customer_id' => $data['customer_id'] ?? 1
                ]);
            foreach($data['objectives'] as $objective) {
                foreach($objective as $key => $value) {
                    $order->objectives()->save(new OrderAttribute(['key' => $key, 'value' => $value]));
                }
            }
            return response()->success();
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
            $query = Order::filter($request)
                ->latest();
            if($request->user()->type != 'admin') {
                $query->where('customer_id', $request->user()->id);
            }
            $orders = $query->paginate(CommonHelper::perPage($request));
            return response()->success(CommonHelper::parsePaginator($orders));
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function update(array $data, $id)
    {
        try {
            $this->orderRepository->update($data, $id);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_UPDATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function action(OrderActionRequest $request, Order $order)
    {
        try {
            $order->update($request->validated());
            $order->customer->notify(new OrderUpdateNotification($order));
            return response()->success();
        } catch (\Exception $exception) {
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function getOrder(int $id)
    {
        return $this->orderRepository->show($id);
    }
}
