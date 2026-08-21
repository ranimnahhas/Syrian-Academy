<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'الاسم مطلوب',
            'name.max'         => 'الاسم يجب أن لا يتجاوز 255 حرف',
            'email.required'   => 'الإيميل مطلوب',
            'email.email'      => 'صيغة الإيميل غير صحيحة',
            'phone.max'        => 'رقم الهاتف يجب أن لا يتجاوز 20 رقماً',
            'subject.required' => 'الموضوع مطلوب',
            'subject.max'      => 'الموضوع يجب أن لا يتجاوز 255 حرف',
            'message.required' => 'نص الرسالة مطلوب',
            'message.min'      => 'الرسالة يجب أن تكون 10 أحرف على الأقل',
            'message.max'      => 'الرسالة يجب أن لا تتجاوز 2000 حرف',
        ];
    }
}