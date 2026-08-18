<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreLessonRequest;
use App\Http\Requests\Api\V1\UpdateLessonRequest;
use App\Http\Resources\Api\V1\LessonResource;
use App\Services\BunnyStreamService;
use App\Services\EnrollmentService;
use App\Services\LessonService;
use Illuminate\Http\JsonResponse;

class LessonController extends BaseController
{
    public function __construct(
        private LessonService $lessonService,
        private EnrollmentService $enrollmentService,
        private BunnyStreamService $bunnyService
    ) {}

    public function courseLessons(int $courseId): JsonResponse
    {
        $lessons = $this->lessonService->getByCourse($courseId);

        return $this->sendResponse(
            LessonResource::collection($lessons),
            'تم جلب الدروس بنجاح'
        );
    }

public function show(int $id): JsonResponse
{
    $lesson = $this->lessonService->getById($id);

    if (!$lesson) {
        return $this->sendError('الدرس غير موجود');
    }

    $isAdmin = auth()->user()?->role === 'admin';

    if (!$isAdmin && !$this->enrollmentService->isEnrolled(auth()->id(), $lesson->course_id)) {
        return $this->sendError('يجب أن تكون مسجلاً بالكورس لمشاهدة هذا الدرس', [], 403);
    }

    // زيادة عداد المشاهدات
    $this->lessonService->incrementViews($id);

    $videoUrl = $lesson->vimeo_id
        ? $this->bunnyService->getEmbedUrl($lesson->vimeo_id)
        : null;

    return $this->sendResponse([
        'lesson'    => new LessonResource($lesson->fresh()),
        'video_url' => $videoUrl,
    ], 'تم جلب الدرس بنجاح');
}

    public function store(StoreLessonRequest $request): JsonResponse
    {
        $lesson = $this->lessonService->create($request->validated());

        return $this->sendResponse(
            new LessonResource($lesson),
            'تم إنشاء الدرس بنجاح',
            201
        );
    }

    public function update(UpdateLessonRequest $request, int $id): JsonResponse
    {
        $lesson = $this->lessonService->update($id, $request->validated());

        if (!$lesson) {
            return $this->sendError('الدرس غير موجود');
        }

        return $this->sendResponse(
            new LessonResource($lesson),
            'تم تحديث الدرس بنجاح'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->lessonService->delete($id);

        if (!$deleted) {
            return $this->sendError('الدرس غير موجود');
        }

        return $this->sendResponse(null, 'تم حذف الدرس بنجاح');
    }
}