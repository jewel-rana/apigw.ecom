<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        return response()->success([
            'customers' => $this->dashboardService->getCustomerStats($request),
            'orders' => $this->dashboardService->getOrderStats($request),
            'last_seven_days_order_stats' => $this->dashboardService->getLastSevenDaysStats($request)
        ]);
    }
}
