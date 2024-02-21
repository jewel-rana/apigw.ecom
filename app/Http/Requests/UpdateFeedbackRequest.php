<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return CommonHelper::hasPermission('feedback-update');
    }

    public function rules(): array
    {
        return [
            'company' => 'required|string',
            'moto' => 'required|string',
            'name' => 'required|string',
            'designation' => 'required|string',
            'video_link' => 'required|string|url',
            'comments' => 'nullable|string|max:500',
            'remarks' => 'nullable|string',
            'website' => 'nullable|url'
        ];
    }
}
