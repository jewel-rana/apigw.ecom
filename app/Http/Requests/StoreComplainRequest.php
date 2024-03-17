<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreComplainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return CommonHelper::hasPermission('complain-create');
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'title' => 'nullable|string',
            'description' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:Pending,Open,Resolved,Cancelled'
        ];
    }
}
