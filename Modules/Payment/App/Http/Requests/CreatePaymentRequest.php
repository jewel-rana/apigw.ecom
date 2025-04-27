<?php

namespace Modules\Payment\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'gateway_id' => 'required|integer|in:1,2,3'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
