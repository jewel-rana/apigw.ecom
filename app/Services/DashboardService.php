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

    public function getYearlyOrderGraph(Request $request): array
    {
        $data = $this->makeForMonthly($request->input('month', 12));
        $orders = $this->getMonthlyOrders($request->input('month', 12));
        foreach ($orders as $order) {
            $data[$order->month][strtolower($order->status)] = (int)$order->total;
            $data[$order->month]['total'] += (int) $order->total;
        }

        return $data;
    }

    public function getYearlyCustomerGraph(Request $request): array
    {
        $data = $this->makeForMonthly($request->input('month', 12));
        $customers = $this->getMonthlyCustomers($request->input('month', 12));
        foreach ($customers as $customer) {
            $data[$customer->month][strtolower($customer->status)] = (int)$customer->total;
            $data[$customer->month]['total'] += (int)$customer->total;
        }

        return $data;
    }

    private function getYearlyOrders()
    {
        $key = 'yearly_orders_' . $this->year;
        Cache::forget($key);
        return Cache::remember($key, 3600, function () {
            return Order::select(DB::raw("MONTH(created_at) as month, SUM(amount) as total, status"))
                ->whereBetween('created_at', [$this->startOfYear, $this->endOfYear])
                ->groupBy('month', 'status')
                ->get();
        });
    }

    private function getMonthlyOrders($months = 6)
    {
        $key = 'monthly_orders_' . $months;
        Cache::forget($key);
        return Cache::remember($key, 3600, function () use($months) {
            return Order::select(DB::raw("DATE_FORMAT(created_at, '%M-%Y') as month, SUM(amount) as total, status"))
                ->whereBetween('created_at', [now()->subMonths($months - 1)->startOfMonth(), now()->endOfDay()])
                ->groupBy('month', 'status')
                ->get();
        });
    }

    private function getYearlyCustomers()
    {
        $key = 'yearly_customers_' . $this->year;
        Cache::forget($key);
        return Cache::remember($key, 3600, function () {
            return Customer::select(DB::raw("MONTH(created_at) as month, COUNT(*) as total, status"))
                ->whereBetween('created_at', [$this->startOfYear, $this->endOfYear])
                ->groupBy('month', 'status')
                ->get();
        });
    }

    private function getMonthlyCustomers($months = 6)
    {
        $key = 'monthly_customers_' . $months;
        Cache::forget($key);
        return Cache::remember($key, 3600, function () use($months) {
            return Customer::select(DB::raw("DATE_FORMAT(created_at, '%M-%Y') as month, COUNT(*) as total, status"))
                ->whereBetween('created_at', [now()->subMonths($months - 1)->startOfMonth(), now()->endOfDay()])
                ->groupBy('month', 'status')
                ->get();
        });
    }

    private function getLabels(array $values = ['pending', 'active', 'inactive']): array
    {
        $max = ($this->year == now()->format('Y')) ? now()->subMonth()->format('m') : 11;
        $data = [];
        for ($i = $max; $i >= 0;) {
            $data[now()->subMonths($i)->format('F')] = [
                'active' => 0,
                'pending' => 0,
                'inactive' => 0,
                'publish' => 0,
                'refunded' => 0,
                'total' => 0
            ];
            $i--;
        }

        return $data;
    }

    public function getOrderStats(Request $request): array
    {
        return Cache::remember('order_stats', 30 * 60, function () {
            $array = ['publish' => 0, 'refunded' => 0, 'pending' => 0, 'complete' => 0];
            Order::select(DB::raw('count(*) as total, status'))->groupBy('status')
                ->get()
                ->each(function ($order, $key) use (&$array) {
                    $status = strtolower($order->status);
                    if(array_key_exists($status, $array)) {
                        $array[$status] = $order->total;
                    }
                });
            return $array;
        });
    }

    public function getCustomerStats(Request $request): array
    {
        return Cache::remember('customer_stats', 30 * 60, function () {
            $array = ['active' => 0, 'inactive' => 0, 'pending' => 0];
            Customer::select(DB::raw('count(*) as total, status'))->groupBy('status')
                ->get()
                ->each(function ($customer, $key) use (&$array) {
                    $array[strtolower($customer->status)] = $customer->total;
                });

            return $array;
        });
    }


    private function makeForMonthly($months = 6): array
    {
        $data = [];
        for($i = $months - 1; $i >= 0;) {
            $data[now()->subMonths($i)->format('F-Y')] = [
                'active' => 0,
                'pending' => 0,
                'inactive' => 0,
                'publish' => 0,
                'refunded' => 0,
                'total' => 0
            ];
            $i--;
        }

        return $data;
    }

    private function makeForDaily($days = 10): array
    {
        $data = [];
        for($i = $days - 1; $i > 0;) {
            $data[now()->subDays($i)->format('Y-m-d')] = [
                'active' => 0,
                'pending' => 0,
                'inactive' => 0,
                'publish' => 0,
                'refunded' => 0,
                'total' => 0
            ];
            $i--;
        }

        return $data;
    }
}
