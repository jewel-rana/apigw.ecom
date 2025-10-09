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
            'supplier_id' => 'nullable|integer|exists:providers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'purchase_price' => 'required|numeric|min:0',
            'strike_price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:512',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'tags' => 'nullable|array',
            'variants' => ['nullable', 'array'],
            'remarks' => 'nullable|string',
            'is_featured' => 'required|boolean',
            'weight' => 'nullable|numeric',
            'badge' => 'nullable|string',
            'related_product_ids' => ['bail|nullable|array'],
            'related_product_ids.*' => 'integer|exists:products,id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
