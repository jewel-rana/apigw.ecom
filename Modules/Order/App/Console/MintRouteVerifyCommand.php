<?php

namespace Modules\Order\App\Console;

use App\Helpers\LogHelper;
use App\Processor\Kartat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MintRouteVerifyCommand extends Command
{
    protected $signature = 'mintroute:transaction-verify';
    protected $description = 'Mintroute Issue verify times.';

    public function handle(): void
    {
        try {
            DB::table('issues_vouchares')
                ->select([
                    'issues_vouchares.id',
                    'issues_vouchares.orderid',
                    'order_items.id as order_item_id'
                ])
                ->leftJoin('order_items', 'order_items.order_id', '=', 'issues_vouchares.orderid')
                ->where('issues_vouchares.m_date_time', '=', '')
                ->cursor()
                ->each(function ($item){
                    try {
                        $array = (new Kartat())->get(
                            'https://api.kartat.io/api/v1/stock/purchase/mintroute/' . $item->orderid . $item->order_item_id,
                            ['order_id' => $item->orderid]);
                        if (array_key_exists('mintInfo', $array['data'])) {
                            foreach ($array['data']['mintInfo']['response'] as $key => $value) {
                                $res = array_values($value);
                                $payload = [
                                    'm_date_time' => $res[0]['order_date'],
                                    'k_date_time' => $array['data']['mintInfo']['utc_date_time'] ?? null,
                                    'a_date_time' => $array['data']['mintInfo']['app_date_time'] ?? null,
                                ];
                                DB::table('issues_vouchares')
                                    ->where('id', $item->id)
                                    ->update($payload);
                            }
                        }
                    } catch (\Exception $e) {
                        LogHelper::error($e->getMessage(), [
                            'order_id' => $item->orderid,
                            'keyword' => 'ITEM verify exception'
                        ]);
                    }
                    sleep(1);

                });
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            LogHelper::error($exception->getMessage(), [
                'keyword' => 'MINT_ROUTE_TRANSACTION_VERIFY_EXCEPTION',
            ]);
        }
    }
}
