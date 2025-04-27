<?php

namespace Modules\Order\App\Http\Requests\Api;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Order\App\Rules\CreateOrderRule;

class StoreOrderRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', new CreateOrderRule()],
            'info' => ['required', 'array'],
            'info.name' => 'required|string',
            'info.email' => 'required|email',
            'info.country_id' => 'nullable',
            'info.city_id' => 'nullable',
            'info.code' => 'nullable|string',
            'info.address' => 'nullable|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
