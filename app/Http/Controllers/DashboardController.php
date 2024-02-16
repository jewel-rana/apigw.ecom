<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\CustomerService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private CustomerService $customerService;
    private OrderService $orderService;

    public function __construct(CustomerService $customerService, OrderService $orderService)
    {
        $this->customerService = $customerService;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        return response()->success([
            'customers' => $this->getCustomerStats($request),
            'orders' => $this->getOrderStats($request),
            'last_seven_days_order_stats' => $this->getLastSevenDaysStats($request)
        ]);
    }

    private function getLastSevenDaysStats(Request $request): array
    {
        return [
            'labels' => [],
            'numbers' => []
        ];
    }

    private function getOrderStats(Request $request)
    {
        return Order::select(DB::raw('count(*) as total, status'))->groupBy('status')
            ->get()
            ->map(function (Order $order) {
                return [
                    $order->status => $order->total
                ];
            });
    }

    private function getCustomerStats(Request $request)
    {
        return Customer::select(DB::raw('count(*) as total, status'))->groupBy('status')
            ->get()
            ->map(function (Customer $customer) {
                return [
                    $customer->status => $customer->total
                ];
            });
    }
}
