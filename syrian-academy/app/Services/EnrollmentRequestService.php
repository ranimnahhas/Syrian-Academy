<?php

namespace App\Services;

use App\Repositories\EnrollmentRequestRepository;

class EnrollmentRequestService
{
    public function __construct(
        private EnrollmentRequestRepository $enrollmentRequestRepository
    ) {}

    public function getAll()
    {
        return $this->enrollmentRequestRepository->getWithDetails();
    }

    public function getById(int $id)
    {
        return $this->enrollmentRequestRepository->find($id, ['user', 'course', 'confirmer']);
    }

    public function getByUser(int $userId)
    {
        return $this->enrollmentRequestRepository->getByUser($userId);
    }

    public function create(array $data, int $userId)
    {
        // هل عنده طلب سابق؟
        if ($this->enrollmentRequestRepository->checkExisting($userId, $data['course_id'])) {
            return null;
        }

        $data['user_id'] = $userId;
        $data['status'] = 'pending';

        return $this->enrollmentRequestRepository->create($data);
    }
}