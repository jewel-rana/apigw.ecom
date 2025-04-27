<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'provider_id' => 'required|exists:providers,id',
            'status' => 'required|string|in:Pending,Completed,Canceled',
            'currency' => 'required|string',
            'exchange_rate' => 'nullable|numeric',
            'item.operator_id.*' => 'required|exists:operators,id',
            'item.bundle_id.*' => 'nullable|exists:bundles,id',
            'item.unit_price.*' => 'required|numeric',
            'item.quantity.*' => 'required|integer',
            'item.amount.*' => 'required|numeric'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
