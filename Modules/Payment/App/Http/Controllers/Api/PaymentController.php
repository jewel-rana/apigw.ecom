<?php

namespace Modules\Payment\App\Http\Controllers\Api;

use App\Gateways\FIB;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Gateway\Entities\Gateway;
use Modules\Payment\App\Http\Requests\CreatePaymentRequest;
use Modules\Payment\App\Models\Payment;
use Modules\Payment\App\Services\PaymentService;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(CreatePaymentRequest $request)
    {
        return $this->paymentService->create($request);
    }

    public function verify(Payment $payment): array
    {
        return $this->paymentService->verify($payment);
    }

    public function checkStatus(Request $request, $gatewayTrxId)
    {
        return $this->paymentService->checkStatus($gatewayTrxId);
    }

    public function ipn(Request $request, Payment $payment, Gateway $gateway): array
    {
        return $this->paymentService->ipn($payment, $gateway, $request);
    }

    public function fibPaymentVerify(Payment $payment): array
    {
        $data = ['status' => false, 'message' => null];
        try {
            $data = $this->paymentService->fibPaymentVerify($payment, $data);
        } catch (\Exception $exception) {
            LogHelper::error($exception, [
                'keyword' => 'FIB_PAYMENT_VERIFY_API_EXCEPTION',
            ]);
            $data['message'] = $exception->getMessage();
        }
        return $data;
    }

}
