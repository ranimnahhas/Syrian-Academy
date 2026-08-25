<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', "unique:categories,slug,{$categoryId}"],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'is_active'   => ['boolean'],
        ];
    }
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
    public function messages(): array
    {
        return [
            'name.max'      => 'الاسم يجب أن لا يتجاوز 255 حرف',
            'slug.unique'   => 'الرابط مستخدم مسبقاً',
            'image.image'   => 'الملف يجب أن يكون صورة',
            'image.mimes'   => 'الصورة يجب أن تكون jpeg, png, jpg, gif, svg',
            'image.max'     => 'حجم الصورة يجب أن لا يتجاوز 2MB',
        ];
    }
}