<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class AnswerLessonQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answer' => ['required', 'string', 'min:2', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'answer.required' => 'نص الإجابة مطلوب',
            'answer.min'      => 'الإجابة يجب أن تكون حرفين على الأقل',
            'answer.max'      => 'الإجابة يجب أن لا تتجاوز 1000 حرف',
        ];
    }
}