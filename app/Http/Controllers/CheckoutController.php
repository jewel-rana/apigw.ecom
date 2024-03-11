<?php

namespace App\Http\Controllers;

use App\Constants\AppConstant;
use App\Gateway\Bkash;
use App\Gateway\Nagad;
use App\Gateway\Sslcom;
use App\Helpers\LogHelper;
use App\Models\Order;
use App\Models\Payment;
use App\Services\GatewayService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController
{
    private OrderService $orderService;
    private PaymentService $paymentService;
    private GatewayService $gatewayService;

    public function __construct(
        PaymentService $paymentService,
        OrderService $orderService,
        GatewayService $gatewayService
    )
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
        $this->gatewayService = $gatewayService;
    }

    public function index(): View
    {
        return view('checkout.fail');
    }

    public function paynow($orderID)
    {
        if((int) $orderID <= 0) {
            return redirect(route('checkout.fail'));
        }
        $order = $this->orderService->getOrder((int) $orderID);
        if(!$order <= 0 && $order->status !== AppConstant::ORDER_PENDING) {
            return redirect(route('checkout.fail'));
        }
        return view('checkout.create', compact('order'));
    }

    public function token(Request $request): JsonResponse
    {
        $data = ['status' => false, 'message' => ''];
        $order = Order::findOrFail($request->order);
        if($order && $order->status === AppConstant::ORDER_PENDING) {
            if($request->input('payment_method') !== null) {
                $paymentMethod = match ($request->input('payment_method')) {
                    'Nagad' => new Nagad(),
                    'Bkash' => new Bkash()
                };
                return $this->gatewayService->token($paymentMethod, $order);
            }
        }
        return response()->json($data);
    }

    public function create(Request $request)
    {
        if($request->input('payment_method') !== null) {
            $paymentMethod = match ($request->input('payment_method')) {
                'Nagad' => new Nagad(),
                'Bkash' => new Bkash()
            };
            return $this->gatewayService->create($paymentMethod, $request->all());
        }
    }

    public function execute(Request $request)
    {
        if($request->input('payment_method') !== null) {
            switch ($request->input('payment_method')) {
                case 'Bkash':
                    $paymentMethod = new Bkash();
                    break;
                case 'Nagad' :
                    $paymentMethod = new Nagad();
                    break;
            }
            return $this->payment->execute($paymentMethod, $request->all());
        } else {
            $paymentMethod = new Sslcom();
            return $this->payment->execute($paymentMethod, $request->all());
        }
    }


    public function fail(): View
    {
        return view('checkout.fail');
    }

    public function success($transactionId): RedirectResponse
    {
        try {
            $transaction = Payment::where('transaction_id', $transactionId)->first();
            if ($transaction === null) {
                throw new \Exception('Transaction record not found!', 404);
            }
            return redirect()->away(config('paths.frontend_site_url') . '/trip-details/' . $transaction->booking_id . '?payment=success');
        } catch (\Exception $exception) {
            LogHelper::error($exception);
            return redirect()->route('checkout.fail');
        }
    }
}
