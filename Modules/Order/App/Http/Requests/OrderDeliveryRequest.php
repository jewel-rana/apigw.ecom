<?php

namespace Modules\Order\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Order\App\Rules\OrderDeliveryRule;

class OrderDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['bail', 'required', 'integer', new OrderDeliveryRule()],
            'delivery_type' => 'bail|required|in:sms,whatsapp',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
