<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCourseRequest;
use App\Http\Requests\Api\V1\UpdateCourseRequest;
use App\Http\Resources\Api\V1\CourseResource;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\V1\SearchCourseRequest;
use App\Services\EnrollmentService;

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
    // كورسات مجانية
    public function free(): JsonResponse
    {
      $courses = $this->courseService->getFree();

        return $this->sendResponse(
          CourseResource::collection($courses),
          'تم جلب الكورسات المجانية بنجاح'
        );
    }

     // كورسات مدفوعة
    public function paid(): JsonResponse
    {
       $courses = $this->courseService->getPaid();
 
       return $this->sendResponse(
         CourseResource::collection($courses),
        'تم جلب الكورسات المدفوعة بنجاح'
      );
    }
    public function latest(): JsonResponse
    {
       $courses = $this->courseService->getLatest(10);

         return $this->sendResponse(
           CourseResource::collection($courses),
           'تم جلب أحدث الكورسات بنجاح'
       );
    }
    public function myCourses(): JsonResponse
    {
        $user = auth()->user();

       // التأكد إن المستخدم معلم
       $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();

       if (!$teacher) {
           return $this->sendError('غير مصرح - يجب أن تكون معلماً', [], 403);
        }

       $courses = $this->courseService->getByTeacher($teacher->id);

       return $this->sendResponse(
           CourseResource::collection($courses),
           'تم جلب كورساتك بنجاح'
       );
    }


    public function search(SearchCourseRequest $request): JsonResponse
    {
       $courses = $this->courseService->search($request->q);

       return $this->sendResponse(
           CourseResource::collection($courses),
          'تم البحث بنجاح'
       );
    }

    public function mostEnrolled(EnrollmentService $enrollmentService): JsonResponse
    {
        $courses = $enrollmentService->getMostEnrolledCourses(10);

        return $this->sendResponse(
            $courses->map(function ($item) {
                return [
                    'course'            => new CourseResource($item->course),
                    'total_enrollments' => $item->total_enrollments,
                ];
            }),
            'تم جلب الكورسات الأكثر تسجيلاً بنجاح'
        );
    }
}