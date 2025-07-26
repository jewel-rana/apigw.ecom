<?php

namespace Modules\Shipping\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:shippings,name' . $this->shipping,
            'code' => 'required|string|unique:shippings,code,' . $this->shipping,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'remarks' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
