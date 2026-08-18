<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'lesson_id'     => $this->lesson_id,
            'lesson_title'  => $this->lesson?->title,
            'user_id'       => $this->user_id,
            'user_name'     => $this->user?->name,
            'question'      => $this->question,
            'answer'        => $this->answer,
            'answer_by'     => $this->answer_by,
            'answerer_name' => $this->answerer?->name,
            'answered_at'   => $this->answered_at,
            'status'        => $this->status,
            'is_public'     => $this->is_public,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}