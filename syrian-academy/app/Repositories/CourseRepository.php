<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}