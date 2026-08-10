<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SearchCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.required' => 'كلمة البحث مطلوبة',
            'q.min'      => 'كلمة البحث يجب أن تكون حرفين على الأقل',
            'q.max'      => 'كلمة البحث يجب أن لا تتجاوز 100 حرف',
        ];
    }
}