<?php


namespace App\Gateway;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;

class Bkash extends Builder implements GatewayInterface, BkashInterface
{
    private array $credentials;
    public function __construct()
    {
        $this->credentials = config('gateway.bkash');
        $this->baseUrl = $this->credentials['base_url'];
    }

    public function token($order)
    {
        $token = Cache::get('bkash.token');
        $data = [
            'status' => false,
            'order' => $order->id,
            'invoice' => $order->id,
            'amount' => $order->amount
        ];
        if(!$token) {
            $response = $this->__setUrl('tokenized/checkout/token/grant')
                ->__setBody(json_encode([
                    'app_key' => $this->credentials['client_id'],
                    'app_secret' => $this->credentials['client_secret']
                ]))
                ->__setHeader(array(
                    'Content-Type:application/json',
                    'password:' . $this->credentials['password'],
                    'username:' . $this->credentials['username']
                ))
                ->_call();

            if (!is_object($response) || !$response->id_token) {
                $data['message'] = 'Token error';
            } else {
                Cache::put('bkash.token', $response->id_token, 300);
                $data['status'] = true;
                $data['token'] = $response->id_token;
            }
        } else {
            $data['status'] = true;
            $data['token'] = $token;
        }
        return $data;
    }

    public function create(Payment $payment)
    {
        $data = ['status' => false];
        $response = $this->__setHeader([
            'Content-Type:application/json',
            'Authorization:' . $this->getToken($payment->order),
            'X-APP-Key:' . $this->credentials['client_id'],
            'X-AMZ-DATE:' . now()->format('Y-m-d')
        ])
            ->__setBody(json_encode([
                'mode' => '0011',
                'payerReference' => $payment->order_id, // pass oderId or anything
                'callbackURL' => url('checkout/callback'),
                'amount' => $payment->amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $payment->order_id
            ]))
            ->__setUrl( 'tokenized/checkout/create')
            ->_call();

        if (is_object($response) && $response->paymentID && $response->bkashURL) {
            $data['status'] = true;
            $data['redirectUrl'] = $response->bkashURL;
            $data['gateway_trx_id'] = $response->paymentID;
        }
        return $data;
    }

    public function execute(Payment $payment)
    {
        return $this->__setUrl('/tokenized/checkout/execute')
            ->__setHeader([
                'Content-Type:application/json',
                'authorization:' . $this->getToken($payment->order),
                'x-app-key:' . $this->credentials['client_id']
            ])
            ->__setBody(json_encode(['paymentID' => $payment->gateway_trx_id]))
            ->_call();
    }

    public function verify(Payment $payment)
    {
        return $this->__setUrl('/tokenized/checkout/payment/status')
            ->__setHeader([
                'Content-Type:application/json',
                'authorization:' . $this->getToken($payment->order),
                'x-app-key:' . $this->credentials['client_id']
            ])
            ->__setBody(json_encode(['paymentID' => $payment->gateway_trx_id]))
            ->_call();
    }

    public function refund(Payment $payment)
    {
        return $this->__setUrl('/tokenized/checkout/payment/status')
            ->__setHeader([
                'Content-Type:application/json',
                'authorization:' . $this->getToken($payment->order),
                'x-app-key:' . getOption('Bkash_app_id')
            ])
            ->__setBody(json_encode(['paymentID' => $payment->gateway_trx_id]))
            ->_call();
    }

    private function getToken(Order $order): ?string
    {
        return $this->token($order)['token'] ?? null;
    }

    public function intend()
    {
        // TODO: Implement intend() method.
    }
}
