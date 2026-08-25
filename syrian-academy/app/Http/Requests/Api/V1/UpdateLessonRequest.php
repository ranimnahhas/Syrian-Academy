<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id'         => ['sometimes', 'exists:courses,id'],
            'title'             => ['sometimes', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'content'           => ['nullable', 'string'],
            'vimeo_id'          => ['nullable', 'string', 'max:255'],
            'vimeo_embed'       => ['nullable', 'string'],
            'video_path'        => ['nullable', 'string', 'max:255'],
            'video_duration'    => ['nullable', 'string', 'max:50'],
            'resource_path'     => ['nullable', 'string', 'max:255'],
            'resource_type'     => ['nullable', 'string', 'max:50'],
        ];
    }
    public function messages(): array
    {
        return [
            'course_id.exists'         => 'الكورس غير موجود',
            'title.max'                => 'العنوان يجب أن لا يتجاوز 255 حرف',
            'short_description.max'    => 'الوصف يجب أن لا يتجاوز 500 حرف',
        ];
    }
}