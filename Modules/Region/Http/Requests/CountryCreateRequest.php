<?php

namespace Modules\Region\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'zone_id' => 'required|integer|exists:time_zones,id',
            'time_zone_id' => 'required|integer|exists:time_zones,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'name' => 'required|string|unique:countries,name',
            'code' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
