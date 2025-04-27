<?php

namespace Modules\Customer\App\Services;

use App\Exports\CustomerDataExport;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Modules\Auth\Constants\AuthConstant;
use Modules\Customer\App\Models\Customer;
use Modules\Customer\App\Repositories\Interfaces\CustomerRepositoryInterface;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderItem;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerService
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getDataTable(Request $request)
    {
        return datatables()->eloquent(
            $this->customerRepository->getModel()->filter($request)
        )
            ->addColumn('action', function ($customer) {
                $btns = '';
                if (CommonHelper::hasPermission(['customer-show'])) {
                    $btns = '<a href="' . route('customer.show', $customer->id) . '" class="btn btn-default"><i class="fa fa-eye"></i></a>';
                }
                if (CommonHelper::hasPermission(['customer-action'])) {
                    if ($customer->status == AuthConstant::CUSTOMER_ACTIVE) {
                        $btns .= '<a href="#" class="btn btn-danger customerAction" data-url="' . route('customer.action', $customer->id) . '" data-action="inactive"><i class="fa fa-times"></i></a>';
                    } else {
                        $btns .= '<a href="#" class="btn btn-success customerAction" data-url="' . route('customer.action', $customer->id) . '" data-action="active"><i class="fa fa-check-circle"></i></a>';
                    }
                }
                return $btns;
            })
            ->toJson();
    }

    public function update(array $data, int $id)
    {
        try {
            $customer = $this->customerRepository->update($data, $id);
            return response()->success(
                $customer->format()
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_PROFILE_UPDATE_EXCEPTION'
            ]);
            return response()->error();
        }
    }

    public function suggestions(Request $request): JsonResponse
    {
        try {
            $data = $this->customerRepository->getModel()->filter($request)
                ->limit(15)
                ->get()
                ->map(function ($customer, $key) {
                    return [
                        'id' => $customer->id,
                        'text' => $customer->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => __('No data!'), 'results' => []]);
        }
    }

    public function exportCustomerData(Request $request)
    {
        $fileName = 'customer-data-'.time().'.csv';

        $callback = function() use ($request){
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Id', 'Name', 'Email', 'Mobile No', 'Status', 'Created At']);

                Customer::filter($request)->chunk(1000, function($rows) use ($file) {
                foreach ($rows as $row) {
                    $selectedData = [
                        $row->id,
                        $row->name,
                        $row->email,
                        $row->mobile,
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


//        return ExcelFacade::download(new CustomerDataExport(),$fileName, Excel::CSV);
    }

    public function exportCustomerOrderData(Request $request, Customer $customer): StreamedResponse
    {
        $fileName = 'customer-order-data-'.time().'.csv';

        $callback = function() use ($request, $customer){
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Order Id', 'Customer Name', 'Transaction ID', 'Operator', 'Bundle', 'Purchase QTY', 'Received', 'Price', 'Status', 'Vouchers']);

            OrderItem::with(['order.customer', 'operator', 'bundle', 'vouchers'])
                ->whereHas('order', function($query) use ($customer) {
                    $query->where('customer_id', $customer->id);
                })
                ->chunk(10, function($rows) use ($file) {
                    foreach ($rows as $row) {
                        $selectedData = [
                            $row->created_at,
                            $row->order_id,
                            $row->order?->customer?->name,
                            $row->order_id . $row->id,
                            $row->operator?->name,
                            $row->bundle?->name,
                            $row->qty,
                            $row->vouchers?->count(),
                            $row->total_price,
                            $row->status,
                            $row->vouchers->map(function($voucher) {
                                return $voucher->format();
                            })->toJson(),
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
