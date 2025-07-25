<?php

namespace Modules\Order\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Order\Rules\CheckoutValidationRule;

class OrderCheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => [
                'required',
                'string',
                'uuid',
                new CheckoutValidationRule()
            ],
            'info.name' => 'nullable|string',
            'info.email' => 'nullable|email',
            'info.mobile' => 'nullable|string|min:11|max:13',
            'shipping_id' => 'required|string|exists:shippings,id',
            'shipping' => 'required|array',
            'gateway_id' => 'required|string|exists:gateways,id',
            'payment.account_number' => 'nullable|string',
            'payment.transaction_id' => 'nullable|string'
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
