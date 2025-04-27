<?php

namespace Modules\Customer\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class CustomerActionRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'action' => 'required|string|in:active,inactive'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
