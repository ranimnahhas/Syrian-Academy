<?php

namespace App\Services;

use App\Repositories\ReviewRepository;

class ReviewService
{
    public function __construct(
        private ReviewRepository $reviewRepository
    ) {}

    public function getByCourse(int $courseId)
    {
        return $this->reviewRepository->getByCourse($courseId);
    }

    public function getPending()
    {
        return $this->reviewRepository->getPending();
    }

    public function create(array $data, int $userId)
    {
        // هل مسجل بالكورس؟
        if (!$this->reviewRepository->isEnrolled($userId, $data['course_id'])) {
            return ['error' => 'يجب أن تكون مسجلاً في الكورس لتتمكن من تقييمه'];
        }

        // هل قيم من قبل؟
        if ($this->reviewRepository->findByUserAndCourse($userId, $data['course_id'])) {
            return ['error' => 'لقد قمت بتقييم هذا الكورس مسبقاً'];
        }

        $data['user_id'] = $userId;
        return $this->reviewRepository->create($data);
    }

    public function approve(int $id)
    {
        $review = $this->reviewRepository->find($id);

        if (!$review) {
            return null;
        }

        $review->approve();
        return $review->fresh(['user', 'course']);
    }

    public function delete(int $id): ?bool
    {
        return $this->reviewRepository->delete($id);
    }
    public function getById(int $id)
{
    return $this->reviewRepository->find($id);
}
}