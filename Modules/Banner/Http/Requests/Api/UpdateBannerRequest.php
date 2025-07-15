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
            'name' => 'required|string|unique:banners,name',
            'label' => 'required|string',
            'remarks' => 'nullable|string',
            'is_default' => 'required|boolean',
            'attachments' => 'nullable|array',
            'banner_url' => 'nullable|url',
            'position' => 'required|integer',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
