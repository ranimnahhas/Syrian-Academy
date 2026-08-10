<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Support\Str;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository
    ) {}

    public function getAll()
    {
        return $this->courseRepository->all();
    }

    public function getById(int $id)
    {
        return $this->courseRepository->find($id);
    }

    public function create(array $data)
{
    $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
    
    // إذا مجاني، السعر 0
    if (isset($data['is_paid']) && !$data['is_paid']) {
        $data['price'] = 0;
    }
    
    return $this->courseRepository->create($data);
}

public function update(int $id, array $data)
{
    $course = $this->courseRepository->find($id);

    if (!$course) {
        return null;
    }

    if (isset($data['title']) && !isset($data['slug'])) {
        $data['slug'] = Str::slug($data['title']);
    }

    // إذا تحول لمجاني، السعر 0
    if (isset($data['is_paid']) && !$data['is_paid']) {
        $data['price'] = 0;
    }

    $course->update($data);
    return $course->fresh(['category', 'teacher.user']);
}
public function getFree()
{
    return $this->courseRepository->getFree();
}

public function getPaid()
{
    return $this->courseRepository->getPaid();
}
public function getLatest(int $limit = 10)
{
    return $this->courseRepository->getLatest($limit);
}
public function getByTeacher(int $teacherId)
{
    return $this->courseRepository->getByTeacher($teacherId);
}
public function search(string $query, int $perPage = 15)
{
    return $this->courseRepository->search($query, $perPage);
}
}