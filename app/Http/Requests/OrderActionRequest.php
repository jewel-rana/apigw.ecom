<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;

class OrderActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return CommonHelper::hasPermission('order-update');
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Publish,Refunded,Complete,Pending'
        ];
    }
}
