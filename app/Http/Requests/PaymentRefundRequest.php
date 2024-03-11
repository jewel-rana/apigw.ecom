<?php

namespace App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRefundRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_id' => 'required|integer|exists:payments,id'
        ];
    }
}
