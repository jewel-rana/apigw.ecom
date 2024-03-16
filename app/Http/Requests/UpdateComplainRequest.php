<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateComplainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return CommonHelper::hasPermission('complain-update');
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'description' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,Open,Solved,Cancelled'
        ];
    }
}
