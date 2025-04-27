<?php

namespace Modules\Order\App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Processor\Kartat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Bundle\Entities\Bundle;
use Modules\Order\App\Constant\OrderDeliveryConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Helpers\OrderHelper;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderItem;
use Modules\Order\App\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Payment\App\Constants\PaymentConstant;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function mostPurchasedItems(int $customerId = null)
    {
        $ids = Bundle::limit(5)->get()->pluck('id')->toArray();
        if ($customerId) {
            $mostPurchased = DB::select(DB::raw("SELECT id, COUNT(id) as total FROM `order_items`
            LEFT JOIN orders ON orders.id = order_items.order_id
            WHERE status = 1 AND order.customer_id = {$customerId} GROUP BY bundle_id ORDER BY total DESC LIMIT 5"));
            if(count($mostPurchased) > 0){
                $ids = array_column((array) $mostPurchased, 'id');
            }
        }
        return $ids;
    }

    public function create($request)
    {
        try {
            $order = null;
            DB::transaction(function () use ($request, &$order) {
                $customer = OrderHelper::getCustomer($request->input('info'));
                $order = $this->orderRepository->create(OrderHelper::buildOrderInfo($request) +
                    $request->input('info') +
                    [
                        'customer_id' => $customer->id ?? null
                    ]);
                $items = OrderHelper::buildOrderItems($request);
                foreach ($items as $item) {
                    $order->items()->save(new OrderItem($item + ['is_remote_voucher' => false]));
                }
            });
            return response()->success(
                $order->format()
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function getDataTable(Request $request)
    {
        return datatables()->eloquent(
            $this->orderRepository->getModel()->withCount(['items'])->filter($request)
        )
            ->addColumn('customer', function ($order) {
                return '<a href="' . route('customer.show', $order->customer_id) . '">' . $order->customer?->name . '</a>';
            })
            ->addColumn('gateway', function ($order) {
                return $order->gateway?->name ?? '---';
            })
            ->addColumn('badge_with_status', function ($order) {
                return $order->badge_with_status;
            })
            ->addColumn('info', function ($order) {
                return $order->info;
            })
            ->addColumn('actions', function ($order) {
                if (CommonHelper::hasPermission(['order-show', 'order-action'])) {
                    return "<a href='" . route('order.show', $order->id) . "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
                }
                return '';
            })
            ->rawColumns(['actions', 'badge_with_status', 'customer'])
            ->toJson();
    }

    public function getMyOrders($request, $id)
    {
        try {
            return response()->success(
                CommonHelper::parsePaginator(
                    $this->orderRepository->getModel()
                        ->withCount('items')
                        ->filter($request, $id)
                        ->latest()
                        ->paginate($request->input('limit', 10))
                )
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'GET_MY_ORDERS_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function getPayload(OrderItem $item)
    {
        try {
            if ($item->order?->isNotOwner()) {
                $data['message'] = __('Sorry! you are not the owner of the property.');
            } else {
                $data['order_id'] = $item->order_id;
                $data['trx_id'] = $item->trx_id ?? $item->order_id . $item->id;
                $data['info'] = $item->itemInfo();
                $data['instruction'] = $item->operator?->how_to_obtain;
                $data['vouchers'] = [];

                if (
                    $item->operator->is_in_app_deliverable
                    && ($item->order?->payment?->status == PaymentConstant::STATUS_SUCCESS)
                    && $item->status == OrderItemConstant::SUCCESS
                ) {
                    if ($item->is_remote_voucher) {
                        $response = (new Kartat())->getVoucher($item);
                        $data['vouchers'] = $response['vouchers'];
                    } else {
                        $data['vouchers'] = $item->vouchers()->limit($item->qty)->get()->map(function ($item) {
                            return $item->format();
                        });
                    }
                }

                return response()->success($data);
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VOUCHER_PAYLOAD_EXCEPTION'
            ]);
            $data['message'] = __('Internal error!');
        }
        return response()->error(['message' => $data['message']]);
    }

    public function getSoldProductTable(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            OrderItem::with(['operator', 'order.customer', 'bundle'])
                ->where('status', OrderItemConstant::SUCCESS)
                ->filter($request)
        )
            ->addColumn('customer_name', function ($item) {
                return '<a href="' . route('customer.show', $item->order?->customer_id) . '">' . $item->order?->customer?->name . '</a>';
            })
            ->addColumn('operator_name', function ($item) {
                return '<a href="' . route('operator.show', $item->operator_id) . '">' . $item->operator->name . '</a>';
            })
            ->addColumn('bundle_name', function ($item) {
                return '<a href="' . route('bundle.show', $item->bundle_id) . '">' . $item->bundle->name . '</a>';
            })
            ->addColumn('nice_status', function ($item) {
                return $item->getRawOriginal('status');
            })
            ->addColumn('payment_status', function ($item) {
                return $item->order->payment->status;
            })
            ->addColumn('actions', function ($item) {
                if (CommonHelper::hasPermission(['order-show', 'order-action'])) {
                    return "<a href='" . route('order.show', $item->order_id) . "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
                }
                return '';
            })
            ->rawColumns(['actions', 'customer_name', 'operator_name', 'bundle_name'])
            ->toJson();
    }

    public function exportOrderData(Request $request)
    {
        $fileName = 'order-data-' . time() . '.csv';
        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Id', 'Customer Name', 'Payable Amount', 'Payment Status', 'Order Status', 'Created At']);

            Order::with(['customer', 'payment'])
                ->filter($request)
                ->chunk(1000, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        $selectedData = [
                            $row->id,
                            $row->customer->name,
                            $row->total_payable,
                            $row->payment->status ?? "",
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

    public function exportSoldProducts(Request $request)
    {
        // Increase memory and execution limits
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 6000);

        // Disable output buffering
        if (ob_get_length()) {
            ob_end_clean();
        }
        $fileName = 'sold-products-' . time() . '.csv';
        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Id', 'Operator', 'Bundle', 'Face value', 'Sale price', 'Qty',
                'Amount', 'Order At', 'Order Status', 'Payment Status', 'Customer Name',
                'Customer ID', 'Customer Mobile'
            ]);

            OrderItem::with(['operator', 'bundle', 'order.customer', 'payment'])
                ->where('status', OrderItemConstant::SUCCESS)
                ->filter($request)
                ->chunk(10, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        $selectedData = [
                            $row->order_id,
                            $row->operator->name,
                            $row->bundle->name,
                            $row->unit_price,
                            $row->unit_price,
                            $row->qty,
                            $row->total_price,
                            $row->created_at,
                            $row->status,
                            $row->payment->status ?? "",
                            $row->order->customer->name ?? '',
                            $row->order->customer->id ?? '',
                            $row->order->customer->mobile ?? ''
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

    public function deliver(Order $order, $request): array
    {
        $data = ['status' => false, 'message' => __('Failed')];
        try {
            $delivery = $order->deliveries()->create(
                $request->validated() +
                ['customer_id' => $order->customer_id, 'delivery_to' => $order->customer->mobile]
            );

            foreach($order->items as $item) {
                $data = (new Kartat())->post(config('gateway.kartat.urls.delivery'), [
                    'transaction_id' => $item->order_id . $item->id,
                    'order_id' => $item->order_id,
                    'delivery_address' => $order->customer->mobile,
                    'delivery_method' => $request->input('delivery_type'),
                    'customer_info' => $order->customer->only('id', 'name', 'email', 'mobile'),
                    'email' => $order->customer->email
                ]);
            }

            LogHelper::debug('DELIVERY_RESPONSE', [
                'order_id' => $order->id,
                'customer-id' => $order->customer_id,
                'json' => $data
            ]);

            $delivery->update([
                'status' => $data['status'] ? OrderDeliveryConstant::SUCCESS : OrderDeliveryConstant::FAILED,
                'remarks' => $data['message'],
            ]);
        } catch (\Exception $exception) {
            LogHelper::error($exception, [
                'order_id' => $order->id,
                'keyword' => 'ORDER_DELIVERY_EXCEPTION',
                'payload' => $request->all()
            ]);
        }
        return $data;
    }
}
