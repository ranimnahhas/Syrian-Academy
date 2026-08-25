<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'password'         => ['required', 'string', 'min:8'],
            'specialization'   => ['nullable', 'string', 'max:255'],
            'photo'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'rating'           => ['nullable', 'numeric', 'min:0', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'الاسم مطلوب',
            'email.required'    => 'الإيميل مطلوب',
            'email.unique'      => 'الإيميل مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'email.email'          => 'صيغة الإيميل غير صحيحة',
            'phone.max'            => 'رقم الهاتف يجب أن لا يتجاوز 20 رقماً',
            'photo.image'          => 'الملف يجب أن يكون صورة',
            'photo.mimes'          => 'الصورة يجب أن تكون jpeg, png, jpg, gif, svg',
            'photo.max'            => 'حجم الصورة يجب أن لا يتجاوز 2MB',
            'experience_years.integer' => 'سنوات الخبرة يجب أن تكون رقماً',
            'experience_years.min' => 'سنوات الخبرة يجب أن تكون 0 أو أكثر',
            'rating.numeric'       => 'التقييم يجب أن يكون رقماً',
            'rating.min'           => 'أقل تقييم هو 0',
            'rating.max'           => 'أعلى تقييم هو 5',
         ];
    }
}