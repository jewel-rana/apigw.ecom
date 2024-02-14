<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FrontController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function download(Request $request)
    {
//        return (new OrderExport($request))->raw(Excel::XLSX);
        return (new CustomerExport($request))->download('customers.xlsx');
    }
}
