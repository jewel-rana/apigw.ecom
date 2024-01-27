<?php

namespace App\Services;

use App\Constants\AppConstant;
use App\Models\Promotion;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderService
{
//    private OrderRepositoryInterface $orderRepository;
//
//    public function __construct(OrderRepositoryInterface $orderRepository)
//    {
//        $this->orderRepository = $orderRepository;
//    }

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
}
