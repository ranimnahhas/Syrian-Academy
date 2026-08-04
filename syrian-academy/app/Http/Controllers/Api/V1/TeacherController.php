<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\CourseResource;
use App\Http\Requests\Api\V1\StoreTeacherRequest;
use App\Http\Requests\Api\V1\UpdateTeacherRequest;
use App\Http\Resources\Api\V1\TeacherResource;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;

class TeacherController extends BaseController
{
    public function __construct(
        private TeacherService $teacherService
    ) {}

    public function index(): JsonResponse
    {
        $teachers = $this->teacherService->getAll();

        return $this->sendResponse(
            TeacherResource::collection($teachers),
            'تم جلب المدرسين بنجاح'
        );
    }

    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $teacher = $this->teacherService->create($request->validated());

        return $this->sendResponse(
            new TeacherResource($teacher),
            'تم إنشاء المدرس بنجاح',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $teacher = $this->teacherService->getById($id);

        if (!$teacher) {
            return $this->sendError('المدرس غير موجود');
        }

        return $this->sendResponse(
            new TeacherResource($teacher),
            'تم جلب المدرس بنجاح'
        );
    }

    public function update(UpdateTeacherRequest $request, int $id): JsonResponse
    {
        $teacher = $this->teacherService->update($id, $request->validated());

        if (!$teacher) {
            return $this->sendError('المدرس غير موجود');
        }

        return $this->sendResponse(
            new TeacherResource($teacher),
            'تم تحديث المدرس بنجاح'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->teacherService->delete($id);

        if (is_null($deleted)) {
            return $this->sendError('المدرس غير موجود');
        }

        return $this->sendResponse(null, 'تم حذف المدرس بنجاح');
    }
    public function courses(int $id): JsonResponse
{
    $teacher = $this->teacherService->getById($id);

    if (!$teacher) {
        return $this->sendError('المدرس غير موجود');
    }

    return $this->sendResponse(
        CourseResource::collection($teacher->courses),
        'تم جلب كورسات المدرس بنجاح'
    );
}
}