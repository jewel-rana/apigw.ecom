<?php

namespace Modules\Order\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Notifications\OrderDeliveryNotification;
use Modules\Order\App\Services\OrderService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->orderService->getDataTable($request);
        }
        return view('order::index');
    }

    public function sold(Request $request)
    {
        if($request->ajax()) {
            return $this->orderService->getSoldProductTable($request);
        }
        return view('order::sold');
    }

    public function show(Request $request, Order $order)
    {
        if($request->input('test')) {
            $order->customer->notify(new OrderDeliveryNotification($order->items->first()));
//            $order->customer->notify(new OrderInvoiceNotification($order));
//            event(new PaymentUpdateStatus($order));
        }
        return view('order::show', compact('order'));
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions', 'delete'])) {
            $this->authorize($method, Order::class);
        }
        return parent::callAction($method, $parameters);
    }

    public function export(Request $request)
    {
        return $this->orderService->exportOrderData($request);
    }

    public function export_sold(Request $request)
    {
        return $this->orderService->exportSoldProducts($request);
    }
}
