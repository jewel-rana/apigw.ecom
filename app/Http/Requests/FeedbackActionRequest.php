<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackActionRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return CommonHelper::hasPermission('feedback-action');
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Active,Inactive'
        ];
    }
}
