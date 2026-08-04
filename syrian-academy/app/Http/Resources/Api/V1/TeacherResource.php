<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'user_name'        => $this->user?->name,
            'user_email'       => $this->user?->email,
            'specialization'   => $this->specialization,
            'photo'            => $this->photo ? asset('storage/' . $this->photo) : null,
            'experience_years' => $this->experience_years,
            'rating'           => $this->rating,
            'created_at'       => $this->created_at,
        ];
    }
}