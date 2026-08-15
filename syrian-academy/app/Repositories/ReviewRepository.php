<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $review)
    {
        parent::__construct($review);
    }

    public function getByCourse(int $courseId): Collection
    {
        return $this->model
            ->with('user')
            ->where('course_id', $courseId)
            ->where('is_approved', true)
            ->latest()
            ->get();
    }

    public function getPending(): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'course'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(15);
    }

    public function findByUserAndCourse(int $userId, int $courseId): ?Review
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function isEnrolled(int $userId, int $courseId): bool
    {
        return \App\Models\Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->exists();
    }
}