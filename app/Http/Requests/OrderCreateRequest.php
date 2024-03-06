<?php

namespace App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    use FormValidationResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|integer|exists:customers,id',
            'gender' => 'bail|required|string',
            'min_age' => 'bail|required|integer|min:13|max:90',
            'max_age' => 'bail|required|integer|min:13|max:90',
            'location' => 'bail|nullable|in:All,all,divisions',
            'divisions' => 'bail|required|array',
            'amount' => 'bail|required|integer|min:1000|max:100000',
            'promotion_period' => 'bail|required|integer|min:5|max:90',
            'promotion' => ['bail', 'required', 'string'],
            'promotion_objective' => 'bail|required|string',
            'objectives' => 'bail|nullable|array',
            'promotion_start_date' => 'bail|nullable',
            'promotion_end_date' => 'bail|nullable',
            'note' => 'nullable|string'
        ];
    }
}
