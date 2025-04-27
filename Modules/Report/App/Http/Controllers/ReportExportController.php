<?php

namespace Modules\Report\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Report\App\Models\ReportExport;
use Modules\Report\App\Services\ReportExportService;

class ReportExportController extends Controller
{
    private ReportExportService $exportService;

    public function __construct(ReportExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->exportService->getDataTable($request);
        }
    }

    public function destroy(ReportExport $export)
    {
        try {
            $export->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'REPORT_DESTROY_EXCEPTION'
            ]);
            return response()->failed();
        }
    }
}
