<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Models\Order;
use App\Notifications\OrderCreateNotification;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function download(Request $request)
    {
        $order = Order::first();
        $order->customer->notify(new OrderCreateNotification($order));
//        return (new OrderExport($request))->raw(Excel::XLSX);
        return (new CustomerExport($request))->download('customers.xlsx');
    }
}
