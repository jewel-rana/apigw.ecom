<?php

namespace Modules\Order\App\Helpers;

use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Bundle\Services\BundleService;
use Modules\Cart\App\Services\CartService;
use Modules\Customer\App\Models\Customer;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderItemConstant;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Services\RefundService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OrderHelper
{
    public static function getCustomer(array $data)
    {
        try {
            if ($customer = auth('customer')->user()) {
                return $customer;
            } else {
                return Customer::firstOrCreate(
                    ['email' => $data['email']],
                    $data +
                    [
                        'gender' => 'male',
                        'password' => Hash::make($data['password'] ?? Str::random(8))
                    ]
                );
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ORDER_CUSTOMER_EXCEPTION'
            ]);
            return null;
        }
    }

    public static function buildOrderInfo($request): ?array
    {
        try {
            $data = $request->input('info');
            $cart = app(CartService::class)->getCarts($request);

            if ($cart) {
                $data += [
                    'total_qty' => $cart['total_qty'],
                    'total_amount' => $cart['total_amount'],
                    'discount' => 0,
                    'coupon_discount' => 0,
                    'status' => OrderConstant::PENDING
                ];

                $data['total_payable'] = $data['total_amount'] - ($data['discount'] + $data['coupon_discount']);
            }
            return $data;
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BUILD_ORDER_INFO_EXCEPTION'
            ]);
            return [];
        }
    }

    public static function buildOrderItems($request): array
    {
        $data = [];
        try {
            if (is_array($request->input('items'))) {
                foreach ($request->input('items') as $item) {
                    $product = app(BundleService::class)->get($item['product_id']);
                    $data[] = [
                        'qty' => $item['qty'] ?? 1,
                        'unit_price' => $product->selling_price,
                        'purchase_price' => $product->buying_price ?? 0,
                        'discount' => 0,
                        'coupon_discount' => 0,
                        'total_price' => $product->selling_price * ($item['qty'] ?? 1),
                        'data' => $item['params'] ?? null
                    ];
                }
            }
        } catch (\Exception $exception) {
            $data['message'] = $exception->getMessage();
            LogHelper::exception($exception, [
                'keyword' => 'BUILD_ORDER_INFO_EXCEPTION'
            ]);
        }
        return $data;
    }

    public static function updateOrderStatus(Order $order): void
    {
        $successCount = $order->items->where('status', OrderItemConstant::SUCCESS)->count();
        $failedCount = $order->items->where('status', OrderItemConstant::FAILED)->count();
        $unstableCount = $order->items->where('status', OrderItemConstant::UNSTABLE)->count();

        if ($successCount == $order->total_qty) {
            $order->update(['status' => OrderConstant::COMPLETE]);
        }

        if ($failedCount == $order->total_qty) {
            $order->update(['status' => OrderConstant::FAILED]);
        }

        if ($successCount && $failedCount && !$unstableCount) {
            $order->update(['status' => OrderConstant::PARTIAL]);
        }

        if ($unstableCount) {
            $order->update(['status' => OrderConstant::UNSTABLE]);
        }

        if ($failedCount && !$unstableCount) {
            LogHelper::info('ORDER_REFUND_CREATE', [
                'order-id' => $order->id
            ]);
            if (app(RefundService::class)->create($order)) {
                $order->update(['is_refund_initiated' => true]);
            }
        }
    }
}
