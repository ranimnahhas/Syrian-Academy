<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Support\Str;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository,
        private CacheService $cacheService
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

        if (isset($data['is_paid']) && !$data['is_paid']) {
            $data['price'] = 0;
        }

        $course = $this->courseRepository->create($data);

        $this->clearCoursesCache();

        return $course;
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

        if (isset($data['is_paid']) && !$data['is_paid']) {
            $data['price'] = 0;
        }

        $course->update($data);

        $this->clearCoursesCache();

        return $course->fresh(['category', 'teacher.user']);
    }

    public function delete(int $id): ?bool
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return null;
        }

        $course->delete();

        $this->clearCoursesCache();

        return true;
    }

    public function getFree()
    {
        return $this->cacheService->remember('courses_free', function () {
            return $this->courseRepository->getFree();
        }, 300);
    }

    public function getPaid()
    {
        return $this->cacheService->remember('courses_paid', function () {
            return $this->courseRepository->getPaid();
        }, 300);
    }

    public function getLatest(int $limit = 10)
    {
        $cacheKey = "courses_latest_{$limit}";

        return $this->cacheService->remember($cacheKey, function () use ($limit) {
            return $this->courseRepository->getLatest($limit);
        }, 300);
    }

    public function getByTeacher(int $teacherId)
    {
        return $this->courseRepository->getByTeacher($teacherId);
    }

    public function search(string $query, int $perPage = 15)
    {
        return $this->courseRepository->search($query, $perPage);
    }

    private function clearCoursesCache(): void
    {
        $this->cacheService->forget('courses_free');
        $this->cacheService->forget('courses_paid');
        $this->cacheService->forget('courses_latest_10');
        $this->cacheService->forget('courses_most_viewed_10');
    }
}