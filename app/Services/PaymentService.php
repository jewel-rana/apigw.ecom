<?php

namespace App\Services;

use App\Constants\AppConstant;
use App\Gateway\Bkash;
use App\Gateway\Nagad;
use App\Helpers\LogHelper;
use App\Http\Requests\PaymentCreateRequest;
use App\Http\Requests\PaymentRefundRequest;
use App\Http\Requests\PaymentVerifyRequest;
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
                $payment->update(['gateway_payment_id' => $gatewayResponse['gateway_payment_id']]);
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
                ->where('gateway_payment_id', $request->input('gateway_payment_id'))
                ->first();

            if (!$payment) {
                return response()->error(['message' => __('Invalid payment')]);
            }

            if ($payment->status == AppConstant::PAYMENT_SUCCESS) {
                return response()->error(['message' => __('Your payment already successful')]);
            }

            $gateway = match (strtolower($payment->payment_method)) {
                'nagad' => new Nagad(),
                'bkash' => new Bkash()
            };

            $gatewayResponse = app(GatewayService::class)->execute($gateway, $payment);
            if (is_object($gatewayResponse) && isset($gatewayResponse->paymentID)) {
                $payment->update(['gateway_response' => (array) $gatewayResponse]);
                if ($gatewayResponse->transactionStatus == AppConstant::BKASH_COMPLETED) {
                    $payment->update(['status' => AppConstant::PAYMENT_SUCCESS, 'gateway_trx_id' => $gatewayResponse->trxID]);
                    if(in_array($payment->order->status, ['Pending', 'Inactive', 'Failed'])) {
                        $payment->order->update(['status' => AppConstant::ORDER_ACTIVE]);
                    }
                    return response()->success(['order_id' => $payment->order_id]);
                } else {
                    $payment->update(['status' => AppConstant::PAYMENT_FAILED]);
                    $payment->order->update(['status' => AppConstant::ORDER_INACTIVE]);
                }
            } else {
                if (isset($gatewayResponse->statusCode) && $gatewayResponse->statusCode != 2062) {
                    $payment->update(['status' => AppConstant::PAYMENT_FAILED]);
                    $payment->order->update(['status' => AppConstant::ORDER_INACTIVE]);
                }
            }

            return response()->error(['message' => $gatewayResponse->statusMessage], 422);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->error();
        }
    }

    public function verify(PaymentVerifyRequest $request)
    {
        try {
            $payment = $this->paymentRepository->getModel()
                ->where('gateway_payment_id', $request->input('gateway_payment_id'))
                ->first();

            if (!$payment) {
                return response()->error(['message' => __('Invalid payment')]);
            }

            if ($payment->status == AppConstant::PAYMENT_SUCCESS) {
                return response()->error(['message' => __('Your payment already successful')]);
            }
            $gateway = match (strtolower($payment->payment_method)) {
                'nagad' => new Nagad(),
                'bkash' => new Bkash()
            };

            $gatewayResponse = app(GatewayService::class)->verify($gateway, $payment);

            if (is_object($gatewayResponse) && $gatewayResponse->paymentID) {
                if ($gatewayResponse->transactionStatus == AppConstant::BKASH_COMPLETED) {
                    $payment->update(['status' => AppConstant::PAYMENT_SUCCESS]);
                    if(in_array($payment->order->status, ['Pending', 'Inactive', 'Failed'])) {
                        $payment->order->update(['status' => AppConstant::ORDER_ACTIVE]);
                    }
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

            if (is_object($gatewayResponse) && $gatewayResponse->refundTrxID) {
                $payment->update(['status' => AppConstant::PAYMENT_REFUNDED]);
                return response()->success(['order_id' => $payment->order_id]);
            }

            return response()->error();
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->error();
        }
    }
}
