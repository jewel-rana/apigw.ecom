<?php

namespace Modules\Order\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderCheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => [
                'required',
                'string',
                'uuid'
            ],
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string|min:11|max:13',
            'delivery' => 'nullable|array',
            'shipping_method' => 'required|string|exists:shippings,code',
            'shipping_method.account_number' => 'nullable|string',
            'shipping_method.transaction_id' => 'nullable|string'
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
