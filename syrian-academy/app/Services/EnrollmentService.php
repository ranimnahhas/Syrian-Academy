<?php

namespace App\Services;

use App\Repositories\EnrollmentRepository;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository
    ) {}

    public function isEnrolled(int $userId, int $courseId): bool
    {
        return $this->enrollmentRepository->isEnrolled($userId, $courseId);
    }
}