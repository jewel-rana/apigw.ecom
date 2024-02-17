<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private int $year;
    private Carbon $startOfYear;
    private Carbon $endOfYear;
    public function getLastSevenDaysStats(Request $request): array
    {
        $this->year = $request->input('year', now()->format('Y'));
        $this->startOfYear = Carbon::createFromFormat('Y-m-d', date($this->year . '-m-d'))->startOfYear();
        $this->endOfYear = ($this->year == now()->format('Y')) ? now()->endOfDay() : $this->startOfYear->endOfYear();
        $data = $this->getLabels();
        $orders = $this->getYearlyOrders();
        foreach($orders as $order) {
            $data[$order->month][strtolower($order->status)] = (int) $order->total;
            $data[$order->month]['total'] += (int) $order->total;
        }

        return $data;
    }

    private function getYearlyOrders()
    {
        $key = 'yearly_orders_' . $this->year;
        return Cache::remember($key, 3600, function() {
            return Order::select(DB::raw("MONTH(created_at) as month, SUM(amount) as total, status"))
                ->whereBetween('created_at', [$this->startOfYear->toString(), $this->endOfYear->toString()])
                ->groupBy('month', 'status')
                ->get();
        });
    }

    private function getLabels(): array
    {
        $max = ($this->year == now()->format('Y')) ? now()->subMonth()->format('m') : 11;
        $data = [];
        for($i = $max; $i >= 0;) {
            $data[now()->subMonths($i)->format('F-Y')] = [
                'active' => 0,
                'pending' => 0,
                'inactive' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'refunded' => 0,
                'total' => 0
            ];
            $i--;
        }

        return $data;
    }

    public function getOrderStats(Request $request): array
    {
//        return Cache::remember('order_stats', 30*60, function() {
            $array = ['active' => 0, 'inactive' => 0, 'pending' => 0, 'completed' => 0, 'cancelled' => 0, 'hold' => 0];
            Order::select(DB::raw('count(*) as total, status'))->groupBy('status')
                ->get()
                ->each(function ($order, $key) use (&$array) {
                    $array[strtolower($order->status)] = $order->total;
                });
            return $array;
//        });
    }

    public function getCustomerStats(Request $request): array
    {
//        return Cache::remember('customer_stats', 30*60, function() {
            $array = ['active' => 0, 'inactive' => 0, 'pending' => 0];
            Customer::select(DB::raw('count(*) as total, status'))->groupBy('status')
                ->get()
                ->each(function ($customer, $key) use (&$array) {
                    $array[strtolower($customer->status)] = $customer->total;
                });

            return $array;
//        });
    }
}
