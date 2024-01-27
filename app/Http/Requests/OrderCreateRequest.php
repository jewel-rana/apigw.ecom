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
            'gender' => 'required|string|in:male,female,all',
            'min_age' => 'required|integer|min:13|max:90',
            'max_age' => 'required|integer|min:13|max:90',
            'amount' => 'bail|required|integer|min:1000|max:100000',
            'promotion_period' => 'bail|required|integer|min:5|max:90',
            'promotion_id' => ['required', 'integer', 'exists:promotions,id'],
            'promotion_objective_id' => 'bail|required|integer|exists:promotion_objectives,id',
            'objectives' => 'bail|required|array'
        ];
    }
}
