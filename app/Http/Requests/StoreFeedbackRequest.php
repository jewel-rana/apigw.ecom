<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return CommonHelper::hasPermission('feedback-create');
    }

    public function rules(): array
    {
        return [
            'company' => 'required|string',
            'moto' => 'nullable|string',
            'name' => 'required|string',
            'designation' => 'nullable|string',
            'video_link' => 'required|string|url',
            'comments' => 'nullable|string|max:500',
            'remarks' => 'nullable|string',
            'website' => 'nullable|url'
        ];
    }
}
