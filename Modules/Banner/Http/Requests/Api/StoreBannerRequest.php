<?php

namespace Modules\Banner\Http\Requests\Api;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'title' => 'required|string|unique:banners,title',
            'medium_text' => 'nullable|string',
            'small_text' => 'nullable|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string|in:Active,Inactive',
            'attachment' => 'required|image|mimes:jpeg,jpg,png,gif',
            'banner_url' => 'nullable|url',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
