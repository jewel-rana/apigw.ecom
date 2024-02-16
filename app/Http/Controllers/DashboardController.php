<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\CustomerService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

    private function getOrderStats(Request $request): array
    {
        return Cache::remember('order_stats', 30*60, function() {
            $array = ['active' => 0, 'inactive' => 0, 'pending' => 0, 'completed' => 0, 'cancelled' => 0, 'hold' => 0];
            Order::select(DB::raw('count(*) as total, status'))->groupBy('status')
                ->get()
                ->each(function ($order, $key) use (&$array) {
                    $array[strtolower($order->status)] = $order->total;
                });
            return $array;
        });
    }

    private function getCustomerStats(Request $request): array
    {
        return Cache::remember('customer_stats', 30*60, function() {
            $array = ['active' => 0, 'inactive' => 0, 'pending' => 0];
            Customer::select(DB::raw('count(*) as total, status'))->groupBy('status')
                ->get()
                ->each(function ($customer, $key) use (&$array) {
                    $array[strtolower($customer->status)] = $customer->total;
                });

            return $array;
        });
    }
}
