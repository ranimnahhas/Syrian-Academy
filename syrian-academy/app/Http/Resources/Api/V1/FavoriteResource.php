<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'course_id'   => $this->course_id,
            'course'      => new CourseResource($this->whenLoaded('course')),
            'note'        => $this->note,
            'position'    => $this->position,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}