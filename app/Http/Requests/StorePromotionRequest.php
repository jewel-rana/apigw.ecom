<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return CommonHelper::hasPermission('promotion-create');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
