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
    public function getMostEnrolledCourses(int $limit = 10)
    {
        return \App\Models\Enrollment::select('course_id', \DB::raw('COUNT(*) as total_enrollments'))
            ->where('is_active', true)
            ->with('course.category', 'course.teacher.user')
            ->groupBy('course_id')
            ->orderByDesc('total_enrollments')
            ->limit($limit)
            ->get();
    }
}