<?php

namespace Modules\Payment\App\Services;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Modules\Payment\App\Models\PaymentLog;

class PaymentIpnService
{
    public function fib(Request $request)
    {
        try {
            LogHelper::critical('FIB_IPN_REQUEST_PAYLOAD', [
                'request-payload' => $request->all()
            ]);
            $paymentLog = PaymentLog::where('gateway_payment_id', $request->input('id'))->first();
            $payment = $paymentLog->payment;
            $gateway = $paymentLog->payment?->gateway;
            LogHelper::critical($gateway, [
                'keyword' => 'FIB_IPN_GATEWAY'
            ]);
            if($gateway) {
                return app(PaymentService::class)->ipn($payment, $gateway, $request);
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FIB_IPN_EXCEPTION'
            ]);
         return response()->failed();
        }
    }
}
