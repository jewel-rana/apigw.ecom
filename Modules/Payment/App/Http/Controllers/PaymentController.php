<?php

namespace Modules\Payment\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\Gateway\Entities\Gateway;
use Modules\Operator\Entities\Operator;
use Modules\Payment\App\Models\Payment;
use Modules\Payment\App\Services\PaymentService;

class PaymentController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->paymentService->getDataTable($request);
        }
        return view('payment::index');
    }

    public function show(Payment $payment)
    {
        return view('payment::show', compact('payment'));
    }

    public function edit($id)
    {
        return view('payment::edit');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestion'])) {
            $this->authorize($method, Payment::class);
        }
        return parent::callAction($method, $parameters);
    }
}
