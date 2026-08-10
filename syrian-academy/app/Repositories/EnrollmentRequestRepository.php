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
    public function findByCode(string $code, int $userId)
    {
       return $this->model
           ->where('payment_code', $code)
           ->where('user_id', $userId)
           ->where('status', 'paid')
           ->where('code_expires_at', '>', now())
           ->first();
    }
    public function filter(array $filters)
{
    $query = $this->model->with(['user', 'course', 'confirmer']);

    // فلترة حسب الحالة
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    // فلترة حسب الكورس
    if (!empty($filters['course_id'])) {
        $query->where('course_id', $filters['course_id']);
    }

    // فلترة حسب المستخدم
    if (!empty($filters['user_id'])) {
        $query->where('user_id', $filters['user_id']);
    }

    // فلترة حسب التاريخ
    if (!empty($filters['date_from'])) {
        $query->whereDate('created_at', '>=', $filters['date_from']);
    }
    if (!empty($filters['date_to'])) {
        $query->whereDate('created_at', '<=', $filters['date_to']);
    }

    // بحث
    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhereHas('course', function ($c) use ($search) {
                $c->where('title', 'LIKE', "%{$search}%");
            });
        });
    }

    return $query->latest()->paginate($filters['per_page'] ?? 15);
}
}