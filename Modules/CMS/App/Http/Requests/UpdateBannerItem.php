<?php

namespace Modules\CMS\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerItem extends FormRequest
{
    public function rules(): array
    {
        return [
            'banner_id' => 'required|integer|exists:banners,id',
            'title' => 'required|string',
            'slogan' => 'nullable|string',
            'description' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpg,png,gif,webp',
            'text_size' => 'required|string|in:large,medium,small',
            'text_color' => 'nullable|string',
            'btn_color' => 'nullable|string',
            'btn_text' => 'nullable|string',
            'btn_url' => 'nullable|string'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
