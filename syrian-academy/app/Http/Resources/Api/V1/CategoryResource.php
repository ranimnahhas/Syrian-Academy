<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'image'         => $this->image ? asset('storage/' . $this->image) : null,
            'is_active'     => $this->is_active,
            'courses_count' => $this->whenLoaded('courses', function () {
                return $this->courses->count();
            }, $this->courses()->count()),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}