<?php

namespace Modules\Menu\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class MenuItemAddRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'type' => 'required|string|in:page,service,category'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
