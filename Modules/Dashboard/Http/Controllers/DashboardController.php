<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $statistics = $this->dashboardService->statistics($request);
        return view('dashboard::index', compact('statistics'))->with(['title' => 'Dashboard']);
    }
}
