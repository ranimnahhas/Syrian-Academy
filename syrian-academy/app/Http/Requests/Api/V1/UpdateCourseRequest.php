<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_paid')) {
            $this->merge([
                'is_paid' => filter_var($this->is_paid, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function rules(): array
    {
        $courseId = $this->route('course');

        return [
            'category_id'       => ['sometimes', 'exists:categories,id'],
            'teacher_id'        => ['sometimes', 'exists:teachers,id'],
            'title'             => ['sometimes', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255', "unique:courses,slug,{$courseId}"],
            'short_description' => ['nullable', 'string', 'max:500'],
            'is_paid'           => ['boolean'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'duration_hours'    => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'التصنيف غير موجود',
            'teacher_id.exists'  => 'المدرس غير موجود',
            'title.max'          => 'العنوان يجب أن لا يتجاوز 255 حرف',
            'slug.unique'        => 'الرابط مستخدم مسبقاً',
            'price.numeric'       => 'السعر يجب أن يكون رقماً',
            'price.min'           => 'السعر يجب أن يكون 0 أو أكثر',
            'duration_hours.integer' => 'المدة يجب أن تكون رقماً',
            'duration_hours.min'  => 'المدة يجب أن تكون 0 أو أكثر',
        ];
    }
}