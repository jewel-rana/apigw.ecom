<?php

namespace Modules\Region\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'country_id' => 'required|integer|exists:countries,id',
            'time_zone_id' => 'required|integer|exists:time_zones,id',
            'name' => 'required|string',
            'code' => 'required|string|unique:cities,code,' . $this->region
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
