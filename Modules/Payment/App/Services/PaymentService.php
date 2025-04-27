<?php

namespace Modules\Payment\App\Services;

use App\Gateways\FIB;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Gateway\Entities\Gateway;
use Modules\Order\App\Repositories\OrderRepository;
use Modules\Payment\App\Constants\PaymentConstant;
use Modules\Payment\App\Events\PaymentUpdateStatus;
use Modules\Payment\App\Events\PaymentVerifiedEvent;
use Modules\Payment\App\Models\Payment;
use Modules\Payment\App\Models\PaymentLog;
use Modules\Payment\App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentService
{
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getDataTable(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->paymentRepository->getModel()->with('gateway')
                ->filter($request)
        )
            ->addColumn('action', function ($payment) {
                if(CommonHelper::hasPermission(['payment-show', 'payment-action'])) {
                    return '<a href="' . route('payment.show', $payment->id) . '" class="btn btn-default"><i class="fa fa-eye"></i></a>';
                }
                return '';
            })
            ->toJson();
    }

    public function create(Request $request)
    {
        try {
            $data = ['status' => false, 'message' => __('Failed')];
            DB::transaction(function () use ($request, &$data) {
                $order = app(OrderRepository::class)->show($request->input('order_id'));
                $payment = $this->paymentRepository->create(
                    $request->only(['gateway_id', 'order_id']) +
                    [
                        'customer_id' => $order->customer_id,
                        'amount' => $order->total_payable,
                        'status' => PaymentConstant::STATUS_PENDING
                    ]
                );
                $gateway = CommonHelper::purseGateway($payment->gateway);
                $data = (new $gateway($payment->gateway))->create($payment, $data);
                $data['payment_id'] = $payment->id;
                $data['order_id'] = $order->id;
            });

            if($data['status']) {
                return response()->success(Arr::except($data, ['status', 'message']));
            }
            return response()->error(['message' => $data['message']]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PAYMENT_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function ipn(Payment $payment, Gateway $gateway, Request $request): array
    {
        $data = ['status' => false, 'message' => 'IPN failed!'];
        try {
            LogHelper::debug($request->all(), [
                'keyword' => 'FIB_IPN'
            ]);
            $payment->logs()->save(new PaymentLog([
                'type' => PaymentConstant::IPN,
                'request_payload' => $request->all(),
                'response_payload' => json_decode(response()->success()->content()),
                'order_id' => $payment->order_id
            ]));

            $gatewayClassName = CommonHelper::purseGateway($gateway);
            LogHelper::critical($gatewayClassName, [
                'keyword' => 'IPN_GATEWAY_CLASS_NAME'
            ]);
            $data = (new $gatewayClassName($gateway))->ipn($payment, $data);
            if(config('payment.broadcast.enabled', false)) {
                event(new PaymentUpdateStatus($payment->order));
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FIB_IPN_CALL_EXCEPTION'
            ]);
        }
        return $data;
    }

    public function verify(Payment $payment): array
    {
        try {
            $data = ['status' => false, 'message' => __('Failed')];
            $gatewayClassName = CommonHelper::purseGateway($payment->gateway);
            $data = (new $gatewayClassName($payment->gateway))->verify($payment, $data);
            event(new PaymentVerifiedEvent($payment, $data));
            LogHelper::debug($data);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PAYMENT_MANUAL_VERIFY_EXCEPTION'
            ]);
        }
        return $data;
    }

    public function checkStatus($gatewayTrxId)
    {
        $data = ['status' => false, 'message' => __('Failed')];
        try {
            $payment = PaymentLog::where('gateway_payment_id', $gatewayTrxId)->first()->payment ?? null;
            if($payment) {
                $gatewayClassName = CommonHelper::purseGateway($payment->gateway);
                $data = (new $gatewayClassName($payment->gateway))->verify($payment, $data);
                $payment->fresh();
                $data['payment'] = $payment->format();
                event(new PaymentVerifiedEvent($payment, $data));
            }
            LogHelper::debug("PAYMENT_STATUS_CHECK ", [
                'payment-data' => $data]
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PAYMENT_MANUAL_VERIFY_EXCEPTION',
                'gateway_payment_id' => $gatewayTrxId,
            ]);
        }
        return response()->json($data)->header('Referrer-Policy', 'no-referrer');
    }

    public function fibPaymentVerify($payment, &$data): array
    {
        return (new FIB())->verify($payment, $data);
    }
}
