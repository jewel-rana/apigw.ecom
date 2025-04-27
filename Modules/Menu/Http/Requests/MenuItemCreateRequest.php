<?php

namespace Modules\Menu\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'menu_id' => 'bail|required|exists:menus,id',
            'name' => 'bail|required',
            'description' => 'bail|nullable|string',
            'css_class' => 'bail|nullable',
            'icon_class' => 'bail|nullable',
            'menu_url' => 'bail|required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
