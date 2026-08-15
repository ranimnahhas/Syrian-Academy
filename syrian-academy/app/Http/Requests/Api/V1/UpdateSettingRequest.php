<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'القيمة مطلوبة',
            'value.max'      => 'القيمة يجب أن لا تتجاوز 500 حرف',
        ];
    }
}