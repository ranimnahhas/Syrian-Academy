<?php

namespace App\Services;

use App\Repositories\ContactRepository;
use App\Repositories\CourseRepository;
use App\Repositories\EnrollmentRequestRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;

class DashboardService
{
    public function __construct(
        private UserRepository $userRepository,
        private CourseRepository $courseRepository,
        private TeacherRepository $teacherRepository,
        private EnrollmentRequestRepository $enrollmentRequestRepository,
        private ContactRepository $contactRepository
    ) {}

    public function getStats(): array
    {
        return [
            'total_students'     => $this->userRepository->countStudents(),
            'total_teachers'     => $this->teacherRepository->count(),
            'total_courses'      => $this->courseRepository->count(),
            'pending_requests'   => $this->enrollmentRequestRepository->countPending(),
            'unread_contacts'    => $this->contactRepository->countUnread(),
            'active_enrollments' => $this->enrollmentRequestRepository->countActive(),
        ];
    }

    public function getRecentRequests(int $limit = 5)
    {
        return $this->enrollmentRequestRepository->getRecent($limit);
    }

    public function getRecentContacts(int $limit = 5)
    {
        return $this->contactRepository->getRecent($limit);
    }
    public function getRevenueStats(): array
    {
        return $this->enrollmentRequestRepository->getRevenueStats();
    }
}