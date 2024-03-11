<?php

namespace App\Services;

use App\Constants\AppConstant;
use App\Gateway\Bkash;
use App\Gateway\Nagad;
use App\Helpers\LogHelper;
use App\Http\Requests\PaymentCreateRequest;
use App\Http\Requests\PaymentRefundRequest;
use App\Http\Requests\PaymentVerifyRequest;
use App\Models\Order;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentService
{
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function create(PaymentCreateRequest $request)
    {
        try {
            $payment = $this->paymentRepository->create($request->validated());
            $gateway = match (strtolower($request->input('payment_method', 'bkash'))) {
                'nagad' => new Nagad(),
                'bkash' => new Bkash()
            };
            $gatewayResponse = app(GatewayService::class)->create($gateway, $payment);
            if ($gatewayResponse['status']) {
                $payment->update(['gateway_trx_id' => $gatewayResponse['gateway_trx_id']]);
                return response()->success([
                    'payment_url' => $gatewayResponse['redirectUrl']
                ]);
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->error();
        }
    }

    public function execute(PaymentVerifyRequest $request)
    {
        try {
            $payment = $this->paymentRepository->getModel()
                ->where('gateway_trx_id', $request->input('gateway_payment_id'))
                ->first();
            if (!$payment) {
                throw new \Exception('Invalid payment');
            }

            if ($payment->status == AppConstant::PAYMENT_SUCCESS) {
                throw new \Exception('Your payment already successful', 422);
            }

            $gateway = match (strtolower($payment->payment_method)) {
                'nagad' => new Nagad(),
                'bkash' => new Bkash()
            };

            $gatewayResponse = app(GatewayService::class)->execute($gateway, $payment);

            if (is_object($gatewayResponse) && $gatewayResponse->paymentID) {
                $payment->update(['gateway_response' => (array) $gatewayResponse]);
                if ($gatewayResponse->transactionStatus == AppConstant::BKASH_COMPLETED) {
                    $payment->update(['status' => AppConstant::PAYMENT_SUCCESS]);
                    return response()->success(['order_id' => $payment->order_id]);
                } else {
                    $payment->update(['status' => AppConstant::PAYMENT_FAILED]);
                }
            }

            return response()->error();
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->error();
        }
    }

    public function verify(PaymentVerifyRequest $request)
    {
        try {
            $payment = $this->paymentRepository->getModel()
                ->where('gateway_trx_id', $request->input('gateway_payment_id'))
                ->first();
            if (!$payment) {
                throw new \Exception('Invalid payment');
            }

            if ($payment->status == AppConstant::PAYMENT_SUCCESS) {
                throw new \Exception('Your payment already successful', 422);
            }

            $gateway = match (strtolower($payment->payment_method)) {
                'nagad' => new Nagad(),
                'bkash' => new Bkash()
            };

            $gatewayResponse = app(GatewayService::class)->verify($gateway, $payment);

            if (is_object($gatewayResponse) && $gatewayResponse->paymentID) {
                if ($gatewayResponse->transactionStatus == AppConstant::BKASH_COMPLETED) {
                    $payment->update(['status' => AppConstant::PAYMENT_SUCCESS]);
                    return response()->success(['order_id' => $payment->order_id]);
                } else {
                    $payment->update(['status' => AppConstant::PAYMENT_FAILED]);
                }
            }

            return response()->error();
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->error();
        }
    }

    public function refund(PaymentRefundRequest $request)
    {
        try {
            $payment = $this->paymentRepository->show($request->input('payment_id'));

            if (!$payment) {
                throw new \Exception('Invalid Order');
            }

            if ($payment->status !== AppConstant::PAYMENT_SUCCESS) {
                throw new \Exception('This payment is not refundable', 422);
            }

            $gateway = match (strtolower($payment->payment_method)) {
                'nagad' => new Nagad(),
                'bkash' => new Bkash()
            };

            $gatewayResponse = app(GatewayService::class)->refund($gateway, $payment);

            dd($gatewayResponse);

            if (is_object($gatewayResponse) && $gatewayResponse->paymentID) {
                if ($gatewayResponse->transactionStatus == AppConstant::BKASH_COMPLETED) {
                    $payment->update(['status' => AppConstant::PAYMENT_REFUNDED]);
                    return response()->success(['order_id' => $payment->order_id]);
                } else {
                    $payment->update(['status' => AppConstant::PAYMENT_FAILED]);
                }
            }

            return response()->error();
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception);
            return response()->error();
        }
    }
}
