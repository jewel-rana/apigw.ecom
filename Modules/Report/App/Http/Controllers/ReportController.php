<?php

namespace Modules\Report\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Operator\Entities\Operator;
use Modules\Report\App\Services\ReportExportService;

class ReportController extends Controller
{
    private ReportExportService $reportService;

    public function __construct(ReportExportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(): Response
    {
        return response()->view('report::index');
    }

    public function transaction(): Response
    {
        return response()->view('report::transaction', ['title' => 'Transaction export']);
    }

    public function order(): Response
    {
        return response()->view('report::order', ['title' => 'Order export']);
    }

    public function customer(): Response
    {
        return response()->view('report::customer', ['title' => 'Customer export']);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->reportService->create($request);
            return redirect()->back()->with(['status' => true, 'message' => __('Success')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'REPORT_EXPORT_EXCEPTION',
                'payload' => $request->all()
            ]);
            return redirect()->back()->with(['status' => false, 'message' => __('Failed to export')]);
        }
    }
}
