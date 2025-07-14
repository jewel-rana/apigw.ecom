<?php

namespace Modules\CMS\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomeCardRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,jpg,png,gif',
            'bg_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'border_color' => 'nullable|string',
            'variant_color' => 'nullable|string',
            'position' => 'required|integer',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
