<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_paid' => $this->has('is_paid')
                ? filter_var($this->is_paid, FILTER_VALIDATE_BOOLEAN)
                : false,
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id'       => ['required', 'exists:categories,id'],
            'teacher_id'        => ['required', 'exists:teachers,id'],
            'title'             => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255', 'unique:courses,slug'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'is_paid'           => ['boolean'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'duration_hours'    => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'التصنيف مطلوب',
            'category_id.exists'   => 'التصنيف غير موجود',
            'teacher_id.required'  => 'المدرس مطلوب',
            'teacher_id.exists'    => 'المدرس غير موجود',
            'title.required'       => 'عنوان الكورس مطلوب',
            'title.max'            => 'العنوان يجب أن لا يتجاوز 255 حرف',
            'slug.unique'          => 'الرابط مستخدم مسبقاً',
        ];
    }
}