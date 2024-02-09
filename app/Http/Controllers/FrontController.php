<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FrontController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
