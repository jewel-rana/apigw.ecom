<?php

namespace Modules\Newsletter\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'email' => 'required|email'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
