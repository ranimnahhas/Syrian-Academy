<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'user_name'         => $this->user?->name,
            'user_email'        => $this->user?->email,
            'user_phone'        => $this->user?->phone,
            'course_id'         => $this->course_id,
            'course_title'      => $this->course?->title,
            'course_price'      => $this->course?->price,
            'status'            => $this->status,
            'rejected_reason'   => $this->rejected_reason,
            'confirmed_by'      => $this->confirmed_by,
            'confirmer_name'    => $this->confirmer?->name,
            'paid_at'           => $this->paid_at,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];

        // الأدمن فقط يشوف الكود
        if ($request->user()?->role === 'admin') {
            $data['payment_code']      = $this->payment_code;
            $data['code_generated_at'] = $this->code_generated_at;
            $data['code_expires_at']   = $this->code_expires_at;
            $data['code_used_at']      = $this->code_used_at;
        }

        return $data;
    }
}