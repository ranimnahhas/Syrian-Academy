<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFavoriteNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'note'      => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'معرف الكورس مطلوب',
            'course_id.exists'   => 'الكورس غير موجود',
            'note.max'           => 'الملاحظة يجب أن لا تتجاوز 500 حرف',
        ];
    }
}