<?php

namespace Modules\Report\App\Console;

use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
use Modules\Report\App\Models\ReportExport;
use Modules\Report\App\Notifications\ReportExportedNotification;
use Modules\Report\App\Services\ReportExportService;

class ReportGenerateCommand extends Command
{
    protected $signature = 'report:generate';
    protected $description = 'Report generate command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $report = ReportExport::where('status', 'pending')->first();
            if($report) {
                $filename = 'report/export/' . $report->type . date('Y-m-d-h-i-s') . '.xlsx';
                $link = (new ReportExportService())->{$report->type}($report, $filename);

                LogHelper::debug($link, [
                    'keyword' => 'REPORT_EXPORT_LINK'
                ]);

                if ($link) {
                    $report->update(['attachment' => $filename, 'status' => 'success']);
                    $report->user->notify(new ReportExportedNotification(
                            $report->user,
                            URL::temporarySignedRoute(
                                'card.download-exported-report',
                                now()->addMinutes(30),
                                ['attachment' => base64_encode($report->attachment)]
                            ))
                    );
                } else {
                    $report->update(['status' => 'failed']);
                }
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'REPORT_GENERATE_COMMAND_EXCEPTION'
            ]);
        }
    }
}
