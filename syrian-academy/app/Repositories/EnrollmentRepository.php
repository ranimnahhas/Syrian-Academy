<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository extends BaseRepository
{
    public function __construct(Enrollment $enrollment)
    {
        parent::__construct($enrollment);
    }

    public function isEnrolled(int $userId, int $courseId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->exists();
    }
}