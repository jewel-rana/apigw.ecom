<?php

namespace Modules\Banner\Http\Requests\Api;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:191',
            'medium_text' => 'nullable|string|max:120',
            'small_text' => 'nullable|string|max:120',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string|in:Active,Inactive',
            'attachment' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'position' => 'required|integer',
            'banner_url' => 'nullable|url',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
