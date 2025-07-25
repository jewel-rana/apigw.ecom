<?php

namespace Modules\CMS\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'remarks' =>  'nullable|string|max:255',
            'type' => 'required|string|in:category,brand,tag,product',
            'model_id' => 'required|string',
            'position' => 'required|integer|max:15',
            'status' => 'nullable|string|in:Active,Inactive',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
