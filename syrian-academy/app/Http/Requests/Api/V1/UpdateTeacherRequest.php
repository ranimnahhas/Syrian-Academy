<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher');
        $teacher = \App\Models\Teacher::find($teacherId);
        $userId = $teacher ? $teacher->user_id : null;

        return [
            'name'             => ['sometimes', 'string', 'max:255'],
            'email'            => ['sometimes', 'string', 'email', 'max:255', "unique:users,email,{$userId}"],
            'phone'            => ['nullable', 'string', 'max:20'],
            'password'         => ['nullable', 'string', 'min:8'],
            'specialization'   => ['sometimes', 'string', 'max:255'],
            'photo'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'experience_years' => ['sometimes', 'integer', 'min:0'],
            'rating'           => ['sometimes', 'numeric', 'min:0', 'max:5'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.max'               => 'الاسم يجب أن لا يتجاوز 255 حرف',
            'email.email'            => 'صيغة الإيميل غير صحيحة',
            'email.unique'           => 'الإيميل مستخدم مسبقاً',
            'phone.max'              => 'رقم الهاتف يجب أن لا يتجاوز 20 رقماً',
            'password.min'           => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'specialization.max'     => 'التخصص يجب أن لا يتجاوز 255 حرف',
            'photo.image'            => 'الملف يجب أن يكون صورة',
            'photo.mimes'            => 'الصورة يجب أن تكون jpeg, png, jpg, gif, svg',
            'photo.max'              => 'حجم الصورة يجب أن لا يتجاوز 2MB',
            'experience_years.integer' => 'سنوات الخبرة يجب أن تكون رقماً',
            'experience_years.min'   => 'سنوات الخبرة يجب أن تكون 0 أو أكثر',
            'rating.numeric'         => 'التقييم يجب أن يكون رقماً',
            'rating.min'             => 'أقل تقييم هو 0',
            'rating.max'             => 'أعلى تقييم هو 5',
        ];
    }
}