<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;

class OrderActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return CommonHelper::hasPermission('order-action');
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Active,Inactive,Cancelled,Completed,Pending'
        ];
    }
}
