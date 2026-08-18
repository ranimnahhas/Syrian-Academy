<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lesson_id' => ['required', 'exists:lessons,id'],
            'question'  => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'lesson_id.required' => 'معرف الدرس مطلوب',
            'lesson_id.exists'   => 'الدرس غير موجود',
            'question.required'  => 'نص السؤال مطلوب',
            'question.min'       => 'السؤال يجب أن يكون 5 أحرف على الأقل',
            'question.max'       => 'السؤال يجب أن لا يتجاوز 1000 حرف',
        ];
    }
}