<?php

namespace Modules\Order\App\Console;

use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Order\App\Models\OrderSummary;
use Symfony\Component\Console\Input\InputOption;

class OrderSummaryOneTimeCommand extends Command
{
    protected $signature = 'order:summary-one-time {--month=}';
    protected $description = 'Command description.';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        try {
            $month = $this->option('month');
            $month = Carbon::createFromFormat('Y-m', $month);
            DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            $statistics = DB::select("SELECT oi.service_type_id, oi.operator_id, oi.bundle_id, DATE_FORMAT(oi.created_at, '%Y-%m-%d') as selling_date,
       SUM(CASE WHEN oi.status = 'success' THEN oi.qty ELSE 0 END) AS success_items,
       SUM(CASE WHEN oi.status = 'failed' THEN oi.qty ELSE 0 END) AS failed_items,
       SUM(CASE WHEN oi.status = 'success' THEN oi.total_price ELSE 0 END) AS success_amount,
       SUM(CASE WHEN oi.status = 'failed' THEN oi.total_price ELSE 0 END) AS failed_amount
        FROM order_items oi
        WHERE oi.created_at BETWEEN '{$month->startOfMonth()}' AND '{$month->endOfMonth()}'
        GROUP BY selling_date, oi.service_type_id, oi.operator_id, oi.bundle_id");
            foreach ($statistics as $statistic) {
                $summary = OrderSummary::updateOrCreate(
                    [
                        'service_type_id' => $statistic->service_type_id,
                        'operator_id' => $statistic->operator_id,
                        'bundle_id' => $statistic->bundle_id,
                        'selling_date' => $statistic->selling_date,
                    ],
                    [
                        'service_type_id' => $statistic->service_type_id,
                        'operator_id' => $statistic->operator_id,
                        'bundle_id' => $statistic->bundle_id,
                        'selling_date' => $statistic->selling_date,
                        'success_items' => $statistic->success_items,
                        'failed_items' => $statistic->failed_items,
                        'success_amount' => $statistic->success_amount,
                        'failed_amount' => $statistic->failed_amount
                    ]
                );
            }
            $this->info("ORDER SUMMARY GENERATED SUCCESSFULLY");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            LogHelper::exception($exception, [
                'keyword' => 'order:summary-exception',
                'date' => date('Y-m-d')
            ]);
        }
    }

    protected function getOptions(): array
    {
        return [
            ['month', null, InputOption::VALUE_OPTIONAL, 'A month option.', null],
        ];
    }
}
