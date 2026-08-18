<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'course_id'         => $this->course_id,
            'course_title'      => $this->course?->title,
            'title'             => $this->title,
            'short_description' => $this->short_description,
            'content'           => $this->content,
            'vimeo_id'          => $this->vimeo_id,
            'vimeo_embed'       => $this->vimeo_embed,
            'video_path'        => $this->video_path,
            'video_duration'    => $this->video_duration,
            'resource_path'     => $this->resource_path,
            'resource_type'     => $this->resource_type,
            'view_count'        => $this->view_count,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}