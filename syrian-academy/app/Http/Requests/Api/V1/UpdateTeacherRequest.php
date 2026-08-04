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
}