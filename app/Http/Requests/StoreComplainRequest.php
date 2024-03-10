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
            'customer_id' => 'required|integer|exists:customers,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,Open,Resolved,Cancelled'
        ];
    }
}
