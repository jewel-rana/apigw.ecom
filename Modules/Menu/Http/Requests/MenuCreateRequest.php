<?php

namespace Modules\Menu\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class MenuCreateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'name' => 'bail|required|unique:menus,name',
            'description' => 'bail|nullable',
            'wrapper_class' => 'bail|nullable|alpha_dash'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
