<?php

namespace Modules\CMS\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'remarks' =>  'nullable|string|max:255',
            'type' => 'required|string|in:category,brand,tag',
            'model_id' => 'required|string',
            'position' => 'required|integer|max:5'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
