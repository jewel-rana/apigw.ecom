<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|integer|exists:customers,id',
            'gender' => 'bail|required|string|in:male,female,both',
            'min_age' => 'bail|required|integer|min:13|max:90',
            'max_age' => 'bail|required|integer|min:13|max:90',
            'location' => 'bail|required|in:All,all,divisions',
            'divisions' => 'bail|required_if:location,divisions|array',
            'amount' => 'bail|required|integer|min:1000|max:100000',
            'promotion_period' => 'bail|required|integer|min:5|max:90',
            'promotion_id' => ['bail', 'required', 'integer', 'exists:promotions,id'],
            'promotion_objective_id' => 'bail|required|integer|exists:promotion_objectives,id',
            'objectives' => 'bail|required|array'
        ];
    }
}
