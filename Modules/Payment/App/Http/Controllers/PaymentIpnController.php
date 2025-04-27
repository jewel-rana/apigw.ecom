<?php

namespace Modules\Payment\App\Http\Controllers;

use App\Gateways\FIB;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payment\App\Services\PaymentIpnService;

class PaymentIpnController extends Controller
{
    private PaymentIpnService $ipnService;

    public function __construct(PaymentIpnService $ipnService)
    {
        $this->ipnService = $ipnService;
    }

    public function fib(Request $request)
    {
        return $this->ipnService->fib($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
