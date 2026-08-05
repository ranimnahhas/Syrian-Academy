<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone'    => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'الإيميل مستخدم مسبقاً',
            'name.max'     => 'الاسم يجب أن لا يتجاوز 255 حرف',
        ];
    }
}