<?php

namespace Modules\CMS\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreBannerItem extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'banner_id' => 'required|integer|exists:banners,id',
            'title' => 'required|string',
            'slogan' => 'nullable|string',
            'description' => 'nullable|string',
            'attachment' => 'required|image|mimes:jpg,png,gif,webp',
            'text_size' => 'nullable|string|in:large,medium,small',
            'text_color' => 'nullable|string',
            'btn_color' => 'nullable|string',
            'btn_text' => 'nullable|string',
            'btn_url' => 'nullable|string',
            'position' => 'nullable|integer',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
