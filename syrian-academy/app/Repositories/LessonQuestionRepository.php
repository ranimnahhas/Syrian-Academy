<?php

namespace App\Repositories;

use App\Models\LessonQuestion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LessonQuestionRepository extends BaseRepository
{
    public function __construct(LessonQuestion $lessonQuestion)
    {
        parent::__construct($lessonQuestion);
    }

    public function getByLesson(int $lessonId): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'answerer'])
            ->where('lesson_id', $lessonId)
            ->where('is_public', true)
            ->latest()
            ->paginate(15);
    }

    public function getMyQuestions(int $userId): LengthAwarePaginator
    {
        return $this->model
            ->with(['lesson', 'answerer'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(15);
    }

    public function getPending(): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'lesson'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);
    }
}