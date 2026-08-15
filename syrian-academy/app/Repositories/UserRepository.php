<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getStudents()
    {
        return $this->model
            ->where('role', 'student')
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);
    }

    public function getTopStudents(int $limit = 10)
    {
        return $this->model
            ->where('role', 'student')
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getNewStudents(int $days = 30)
    {
        return $this->model
            ->where('role', 'student')
            ->where('created_at', '>=', now()->subDays($days))
            ->count();
    }

    public function getStudentsStats(): array
    {
        return [
            'total_students' => $this->model->where('role', 'student')->count(),
            'active_students' => $this->model->where('role', 'student')
                ->whereHas('enrollments', function ($q) {
                    $q->where('is_active', true);
                })->count(),
            'new_students' => $this->getNewStudents(),
            'top_students' => $this->getTopStudents(),
        ];
    }
}