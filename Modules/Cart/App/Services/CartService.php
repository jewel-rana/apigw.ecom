<?php

namespace Modules\Cart\App\Services;

use App\Helpers\LogHelper;
use App\Processor\Kartat;
use Illuminate\Http\Request;
use Modules\Bundle\Entities\Bundle;
use Modules\Cart\App\Models\Cart;
use Modules\Cart\App\Models\CartItem;
use Modules\Operator\Services\OperatorService;
use Modules\Product\Entities\Product;

class CartService
{
    public function getCarts(Request $request)
    {
        try {
            return Cart::with('items')->filter($request)->first()?->format();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_CART_LIST_EXCEPTION'
            ]);
            return null;
        }
    }

    public function create($request)
    {
        try {
            $product = Product::find($request->product_id);
            $priceArr = [
                'price' => $product->price,
                'amount' => $request->input('qty', 1) * $product->price,
                'qty' => $request->input('qty', 1)
            ];

            $cart = Cart::updateOrCreate(
                [
                    'token' => $request->cookie('guest_unique_id')
                ],
                [
                    'customer_id' => auth('api')->id()
                ]
            );
            if ($cart->items()->count()) {
                $existingItem = $cart->items()->where('product_id', $request->input('product_id'));

                if ($item = $existingItem->first()) {
                    $item->update($priceArr);
                } else {
                    $cart->items()->save(
                        new CartItem(
                            $request->validated() + $priceArr
                        )
                    );
                }
            } else {
                $cart->items()->save(
                    new CartItem(
                        $request->validated() + $priceArr
                    )
                );
            }
            return response()->success($cart->format());
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception, [
                'keyword' => 'ADD_TO_CART_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function validate($request): array
    {
        $data = ['status' => false, 'message' => __('Failed')];
        try {
            $operator = app(OperatorService::class)->get($request->input('operator_id'));
            $product = Bundle::find($request->input('product_id'));
            $data = (new Kartat())->validate($operator->getRequestPayload($request, $product), $data);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CART_VALIDATE_EXCEPTION'
            ]);
        }
        return $data;
    }
}
