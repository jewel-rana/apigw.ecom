<?php

namespace Modules\Product\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'supplier_id' => 'nullable|integer|exists:providers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'purchase_price' => 'required|numeric|min:0',
            'strike_price' => 'required|numeric|min:0',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'nullable|array',
            'variants' => ['nullable', 'array'],
            'remarks' => 'nullable|string',
            'is_featured' => 'required|boolean',
            'weight' => 'nullable|numeric',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
