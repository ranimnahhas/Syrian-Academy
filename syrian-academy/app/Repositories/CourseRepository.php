<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository extends BaseRepository
{
    protected array $relations = ['category', 'teacher.user'];

    public function __construct(Course $course)
    {
        parent::__construct($course);
    }

    public function all($columns = ['*'], $relations = null): \Illuminate\Database\Eloquent\Collection
    {
        return parent::all($columns, $relations ?? $this->relations);
    }

    public function paginate(int $perPage = 15, $columns = ['*'], $relations = null): LengthAwarePaginator
    {
        return parent::paginate($perPage, $columns, $relations ?? $this->relations);
    }

    public function find(int $id, $relations = null): ?\Illuminate\Database\Eloquent\Model
    {
        return parent::find($id, $relations ?? $this->relations);
    }
 

    public function getFree(): Collection
    {
       return $this->model
          ->with(['category', 'teacher.user'])
          ->where('is_paid', false)
          ->latest()
          ->get();
    }

    public function getPaid(): Collection
    {
       return $this->model
          ->with(['category', 'teacher.user'])
          ->where('is_paid', true)
          ->latest()
          ->get();
    }
    public function getLatest(int $limit = 10): Collection
    {
       return $this->model
          ->with(['category', 'teacher.user'])
          ->latest()
          ->limit($limit)
          ->get();
    } 
    public function getByTeacher(int $teacherId): Collection
    {
        return $this->model
            ->with(['category'])
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get();
    }


    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
       return $this->model
           ->with(['category', 'teacher.user'])
           ->where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
             ->orWhere('short_description', 'LIKE', "%{$query}%")
              ->orWhereHas('category', function ($cat) use ($query) {
                     $cat->where('name', 'LIKE', "%{$query}%");
                 })
                 ->orWhereHas('teacher.user', function ($user) use ($query) {
                     $user->where('name', 'LIKE', "%{$query}%");
                 });
           })
           ->latest()
           ->paginate($perPage);
    }
    public function count(): int
    {
      return $this->model->count();
    }
    public function getRecent(int $limit = 5)
    {
        return $this->model
            ->latest()
            ->limit($limit)
            ->get();
    }
}