<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'course_id'    => $this->course_id,
            'course_title' => $this->course?->title,
            'user_id'      => $this->user_id,
            'user_name'    => $this->user?->name,
            'rating'       => $this->rating,
            'comment'      => $this->comment,
            'is_approved'  => $this->is_approved,
            'approved_at'  => $this->approved_at,
            'created_at'   => $this->created_at,
        ];
    }
}