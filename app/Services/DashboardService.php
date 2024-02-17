<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getLastSevenDaysStats(Request $request): array
    {
        $data = $this->getLabels();
        $orders = $this->getLastSevenDaysOrders(9);
        foreach($orders as $order) {
            $data[$order->date][strtolower($order->status)] = (int) $order->total;
        }

        return $data;
    }

    private function getLastSevenDaysOrders($days)
    {
//        return Cache::remember('last_ten_days_card_purchases', 3600, function() use ($days) {
            return Order::select(DB::raw("DATE(created_at) as date, SUM(amount) as total, status"))
                ->whereBetween('created_at', [now()->subDays($days)->format('Y-m-d 00:00:00'), now()->subDay()->format('Y-m-d 23:59:59')])
                ->groupBy('date', 'status')
                ->get();
//        });
    }

    private function getLabels(): array
    {
        $data = [];
        for($i = 7; $i > 0;) {
            $data[now()->subDays($i)->format('Y-m-d')] = [
                'active' => 0,
                'pending' => 0,
                'inactive' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'refunded' => 0
            ];
            $i--;
        }

        return $data;
    }

    public function getOrderStats(Request $request): array
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

    public function getCustomerStats(Request $request): array
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
