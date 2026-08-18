<?php

namespace App\Repositories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository extends BaseRepository
{
    public function __construct(Lesson $lesson)
    {
        parent::__construct($lesson);
    }

    public function getByCourse(int $courseId): Collection
    {
        return $this->model
            ->where('course_id', $courseId)
            ->orderBy('id')
            ->get();
    }

    public function incrementViews(int $lessonId): void
    {
        $this->model->where('id', $lessonId)->increment('view_count');
    }
}