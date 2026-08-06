<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFavoritePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'position'  => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'معرف الكورس مطلوب',
            'course_id.exists'   => 'الكورس غير موجود',
            'position.required'  => 'الترتيب مطلوب',
            'position.integer'   => 'الترتيب يجب أن يكون رقماً',
            'position.min'       => 'الترتيب يجب أن يكون 0 أو أكثر',
        ];
    }
}