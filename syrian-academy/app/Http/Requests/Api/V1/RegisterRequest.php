<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ];
    }

    public function messages(): array
    {
            return [
            'name.required'     => 'الاسم مطلوب',
            'name.max'          => 'الاسم يجب أن لا يتجاوز 255 حرف',
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique'      => 'البريد الإلكتروني مستخدم مسبقاً',
            'phone.max'         => 'رقم الهاتف يجب أن لا يتجاوز 20 رقماً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ];
    }
}