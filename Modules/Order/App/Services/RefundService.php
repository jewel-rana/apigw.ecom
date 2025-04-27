<?php

namespace Modules\Order\App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Models\RefundItem;
use Modules\Order\App\Repositories\Interfaces\RefundRepositoryInterface;
use Modules\Payment\App\Constants\PaymentConstant;
use Modules\Payment\App\Constants\PaymentRefundConstant;

class RefundService
{
    private RefundRepositoryInterface $orderRefundRepository;

    public function __construct(RefundRepositoryInterface $orderRefundRepository)
    {
        $this->orderRefundRepository = $orderRefundRepository;
    }

    public function getDataTable($request)
    {
        return datatables()->eloquent(
            $this->orderRefundRepository->getModel()->withCount(['items'])->filter($request)
        )
            ->addColumn('total_payable', function ($refund) {
                return $refund->order->total_payable ?? 0;
            })
            ->addColumn('payment_status', function ($refund) {
                return $refund->payment->status ?? '--';
            })
            ->addColumn('fib_payment_id', function ($refund) {
                return $refund->payment?->createLog?->gateway_payment_id ?? '--';
            })
            ->addColumn('actions', function ($refund) {
                if (CommonHelper::hasPermission(['refund-show', 'refund-action'])) {
                    return "<a href='" . route('refund.show', $refund->id) . "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
                }
                return '';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create($order)
    {
        try {
            if ($order->items->where('status', OrderItemConstant::FAILED)->count() <= 0) {
                LogHelper::critical('ORDER REFUND CREATE FAILED', [
                    'order_id' => $order->id,
                ]);
                throw new \Exception('ORDER REFUND CREATE FAILED (No failed item found)', 501);
            }
            if(Refund::where('order_id', $order->id)->count() > 0){
                LogHelper::critical('ORDER REFUND EXISTS', [
                    'order_id' => $order->id,
                ]);
                $order->update(['is_refund_initiated' => true]);
                throw new \Exception('ORDER REFUND ALREADY EXIST', 501);
            }
            $refund = null;
            DB::transaction(function () use (&$refund, $order) {
                $refund = $this->orderRefundRepository->create(CommonHelper::buildRefundData($order));
                if ($refund) {
                    $refund->items()->saveMany(
                        $order->items->where('status', OrderItemConstant::FAILED)
                            ->map(function ($item) {
                                return new RefundItem($item->only([
                                        'order_id', 'operator_id', 'bundle_id', 'qty'
                                    ]) +
                                    [
                                        'order_item_id' => $item->id,
                                        'amount' => $item->total_price
                                    ]);
                            })
                    );
                }
                $order->update(['is_refund_initiated' => 1]);
            });
            return $refund;
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_REFUND_CREATE_EXCEPTION',
                'order-id' => $order->id,
                'code' => $exception->getCode() ?? 501,
            ]);
            return null;
        }
    }

    public function process(Refund $refund): bool
    {
        try {
            $gateway = CommonHelper::purseGateway($refund->gateway);
            $data = ['status' => false];
            if($refund->order->status != OrderConstant::COMPLETE) {
                $data = (new $gateway)->refund($refund, $data);
                LogHelper::debug('PAYMENT_REFUND_PROCESS_DATA', [
                    'data' => $data,
                    'refund' => $refund,
                ]);
                if ($data['status']) {
                    $refund->update(['status' => PaymentRefundConstant::REFUND_STATUS_INITIATED]);
                }
            }
            return true;
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_REFUND_PROCESS_EXCEPTION'
            ]);
            $refund->increment('attempts', 1);
            return false;
        }
    }

    public function verify(Refund $refund): bool
    {
        try {
            $gateway = CommonHelper::purseGateway($refund->gateway);
            $data = ['status' => false];

            $data = (new $gateway)->verifyRefund($refund, $data);
            LogHelper::debug('PAYMENT_REFUND_PROCESS_DATA', [
                'data' => $data,
                'refund' => $refund,
            ]);
            if($data['status'] && array_key_exists('payment_status', $data) && $data['payment_status'] == 'refunded') {
                $refund->update(['status' => PaymentRefundConstant::REFUND_STATUS_SUCCESS]);
                $refund->order->update(['is_refunded' => true]);
            }
            return true;
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_REFUND_PROCESS_EXCEPTION'
            ]);
            $refund->increment('attempts', 1);
            return false;
        }
    }

    public function exportRefundData(Request $request)
    {
        // Increase memory and execution limits
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 6000);

        // Disable output buffering
        if (ob_get_length()) {
            ob_end_clean();
        }
        $fileName = 'refunds-data-'.time().'.csv';
        $callback = function() use ($request){
            $file = fopen('php://output', 'w');
            fputcsv($file, ['R.Id', 'Customer Name', 'Customer Mobile', 'Order Id', 'FIB P.ID', 'Payable Amount', 'Refund Amount', 'Payment Status', 'Refund Status', 'Created At']);

            Refund::with('order')
                ->filter($request)
                ->chunk(1000, function($rows) use ($file) {
                    foreach ($rows as $row) {
                        $selectedData = [
                            $row->id,
                            $row->order?->customer?->name ?? '--',
                            $row->order?->customer?->mobile ?? '--',
                            $row->order->id,
                            $row->order?->payment?->createLog?->gateway_payment_id ?? '--',
                            $row->order->total_payable,
                            $row->amount,
                            $row->order?->payment?->status ?? '--',
                            $row->status,
                            $row->created_at,
                        ];
                        fputcsv($file, $selectedData);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ]);
    }
}
