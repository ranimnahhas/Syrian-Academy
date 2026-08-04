<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'category_id'       => $this->category_id,
            'category_name'     => $this->category?->name,
            'teacher_id'        => $this->teacher_id,
            'teacher_name'      => $this->teacher?->user?->name,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'short_description' => $this->short_description,
            'is_paid'           => $this->is_paid,
            'price'             => $this->price,
            'duration_hours'    => $this->duration_hours,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}