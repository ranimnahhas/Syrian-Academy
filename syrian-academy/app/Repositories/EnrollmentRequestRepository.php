<?php

namespace App\Repositories;

use App\Models\EnrollmentRequest;

class EnrollmentRequestRepository extends BaseRepository
{
    public function __construct(EnrollmentRequest $enrollmentRequest)
    {
        parent::__construct($enrollmentRequest);
    }

    public function getByUser(int $userId)
    {
        return $this->model
            ->with(['course', 'user'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getWithDetails()
    {
        return $this->model
            ->with(['user', 'course', 'confirmer'])
            ->latest()
            ->get();
    }

    public function checkExisting(int $userId, int $courseId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereIn('status', ['pending', 'paid', 'active'])
            ->exists();
    }
}