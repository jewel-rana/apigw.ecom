<?php

namespace Modules\Provider\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class ProductTagRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'provider_id' => 'required|integer|exists:providers,id',
            'operator_id' => 'required|integer|exists:operators,id',
            'bundle_id' => 'required|integer|exists:bundles,id'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
