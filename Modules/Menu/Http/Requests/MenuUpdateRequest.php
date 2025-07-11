<?php

namespace Modules\Menu\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class MenuUpdateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'name' => 'bail|required|unique:menus,name,' . $this->menu,
            'description' => 'bail|nullable',
            'wrapper_class' => 'bail|nullable|alpha_dash',
            'status' => 'bail|required|string|in:Active,Inactive',
            'remarks' => 'bail|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
