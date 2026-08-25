<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];
    }
        public function messages(): array
    {
        return [
            'user_id.required' => 'معرف المستخدم مطلوب',
            'user_id.exists'   => 'المستخدم غير موجود',
            'title.required'   => 'عنوان الإشعار مطلوب',
            'title.max'        => 'العنوان يجب أن لا يتجاوز 255 حرف',
            'message.required' => 'نص الإشعار مطلوب',
        ];
    }
}