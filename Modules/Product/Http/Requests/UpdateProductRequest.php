<?php

namespace Modules\Product\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'purchase_price' => 'required|numeric|min:0',
            'strike_price' => 'required|numeric|min:0',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
            'attachments' => 'nullable|array',
            'tags' => 'nullable|array',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
