<?php

namespace Modules\Provider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderCashDepositRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'provider_id' => 'required|integer|exists:providers,id',
            'currency' => 'nullable|string|in:IQD,USD',
            'amount' => 'required|integer',
            'voucher_number' => 'required|string|unique:provider_deposits,voucher_number'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
