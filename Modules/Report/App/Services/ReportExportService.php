<?php

namespace Modules\Report\App\Services;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\App\Models\ReportExport;

class ReportExportService
{
    public function getDataTable($request)
    {
        return datatables()->eloquent(
            ReportExport::with('user')->filter($request)
        )
            ->addColumn('criteria', function ($item) {
                $str = "";
                foreach ($item->criteria as $key => $value) {
                    $str .= "<p>" . CommonHelper::beautifyText($key) . ": {$value}</p>";
                }
                return $str;
            })
            ->addColumn('action', function ($report) {
                $str = '';
                if ($report->attachment) {
                    $str .= '<a href="' . URL::temporarySignedRoute(
                            'test',
                            now()->addMinutes(30),
                            ['attachment' => base64_encode($report->attachment)]
                        ) . '" class="btn btn-success"><i class="fa fa-download"></i></a>';
                }
                $str .= '<button class="btn btn-danger deleteExport" data-action="' . route('report.export.destroy', $report->id) . '"><i class="fa fa-times"></i></button>';
                return $str;
            })
            ->rawColumns(['download', 'action', 'criteria'])
            ->toJson();
    }

    public function transaction(ReportExport $report, $filename): void
    {

    }

    public function create(Request $request)
    {
        return ReportExport::create([
            'type' => $request->input('type'),
            'criteria' => $request->except(['type', '_token'])
        ]);
    }
}
