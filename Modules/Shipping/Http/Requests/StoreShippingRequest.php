<?php

namespace Modules\Shipping\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreShippingRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:shippings,code',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'remarks' => 'nullable|string',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
