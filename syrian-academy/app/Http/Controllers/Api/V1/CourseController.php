<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCourseRequest;
use App\Http\Requests\Api\V1\UpdateCourseRequest;
use App\Http\Resources\Api\V1\CourseResource;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController extends BaseController
{
    public function __construct(
        private CourseService $courseService
    ) {}

    public function index(): JsonResponse
    {
        $courses = $this->courseService->getAll();

        return $this->sendResponse(
            CourseResource::collection($courses),
            'تم جلب الكورسات بنجاح'
        );
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create($request->validated());

        return $this->sendResponse(
            new CourseResource($course),
            'تم إنشاء الكورس بنجاح',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $course = $this->courseService->getById($id);

        if (!$course) {
            return $this->sendError('الكورس غير موجود');
        }

        return $this->sendResponse(
            new CourseResource($course),
            'تم جلب الكورس بنجاح'
        );
    }

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        $course = $this->courseService->update($id, $request->validated());

        if (!$course) {
            return $this->sendError('الكورس غير موجود');
        }

        return $this->sendResponse(
            new CourseResource($course),
            'تم تحديث الكورس بنجاح'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->courseService->delete($id);

        if (is_null($deleted)) {
            return $this->sendError('الكورس غير موجود');
        }

        return $this->sendResponse(null, 'تم حذف الكورس بنجاح');
    }
}