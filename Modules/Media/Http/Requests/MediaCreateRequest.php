<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'qqfile' => ['bail', 'required', 'image', 'mimes:jpg,png,webp,gif']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
