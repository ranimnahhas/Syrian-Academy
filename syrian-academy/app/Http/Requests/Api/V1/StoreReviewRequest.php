<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'rating'    => ['required', 'integer', 'min:1', 'max:5'],
            'comment'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'معرف الكورس مطلوب',
            'course_id.exists'   => 'الكورس غير موجود',
            'rating.required'    => 'التقييم مطلوب',
            'rating.integer'     => 'التقييم يجب أن يكون رقماً',
            'rating.min'         => 'أقل تقييم هو 1',
            'rating.max'         => 'أعلى تقييم هو 5',
            'comment.max'        => 'التعليق يجب أن لا يتجاوز 1000 حرف',
        ];
    }
}