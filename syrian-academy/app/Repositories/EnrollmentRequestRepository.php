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
    public function countPending(): int
    { 
         return $this->model->where('status', 'pending')->count();
    }

    public function countActive(): int
    {
         return \App\Models\Enrollment::where('is_active', true)->count();
    }
    public function getRecent(int $limit = 5)
    {
        return $this->model
            ->with(['user', 'course'])
            ->latest()
            ->limit($limit)
            ->get();
    } 
    public function getRevenueStats(): array
{
    $paidRequests = $this->model
        ->whereIn('status', ['paid', 'active'])
        ->whereHas('course', function ($q) {
            $q->where('is_paid', true);
        })
        ->with(['course' => function ($q) {
            $q->where('is_paid', true);
        }])
        ->get();

    return [
        'total_revenue'   => $paidRequests->sum('course.price'),
        'today_revenue'   => $paidRequests->where('paid_at', '>=', today())->sum('course.price'),
        'month_revenue'   => $paidRequests->where('paid_at', '>=', now()->startOfMonth())->sum('course.price'),
        'paid_count'      => $paidRequests->count(),
        'average_payment' => $paidRequests->avg('course.price'),
        'top_course'      => $this->model->whereIn('status', ['paid', 'active'])
            ->whereHas('course', function ($q) {
                $q->where('is_paid', true);
            })
            ->select('course_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('course_id')
            ->orderByDesc('total')
            ->with('course')
            ->first(),
    ];
}   
}