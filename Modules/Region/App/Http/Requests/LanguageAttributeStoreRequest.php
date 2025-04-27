<?php

namespace Modules\Region\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageAttributeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'menu_id' => 'bail|required|integer|exists:menus,id',
            'menu_item_id' => 'bail|required|integer|exists:menu_items,id',
            'name' => 'bail|required|string',
            'description' => 'bail|required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
