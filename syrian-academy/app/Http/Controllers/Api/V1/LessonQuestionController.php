<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AnswerLessonQuestionRequest;
use App\Http\Requests\Api\V1\StoreLessonQuestionRequest;
use App\Http\Resources\Api\V1\LessonQuestionResource;
use App\Services\EnrollmentService;
use App\Services\LessonQuestionService;
use Illuminate\Http\JsonResponse;

class LessonQuestionController extends BaseController
{
    public function __construct(
        private LessonQuestionService $lessonQuestionService,
        private EnrollmentService $enrollmentService
    ) {}

    // عرض أسئلة درس
 public function lessonQuestions(int $lessonId): JsonResponse
{
    $lesson = \App\Models\Lesson::find($lessonId);

    if (!$lesson) {
        return $this->sendError('الدرس غير موجود');
    }

    $user = auth()->user();

    // الطالب: لازم يكون مسجل بالكورس
    if ($user->role === 'student') {
        if (!$this->enrollmentService->isEnrolled($user->id, $lesson->course_id)) {
            return $this->sendError('يجب أن تكون مسجلاً بالكورس لعرض الأسئلة', [], 403);
        }
    }

    // المعلم: لازم يكون مسؤول عن الكورس
    if ($user->role === 'teacher') {
        $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
        $teacherCourse = $teacher 
            ? \App\Models\Course::where('teacher_id', $teacher->id)
                ->where('id', $lesson->course_id)
                ->exists() 
            : false;

        if (!$teacherCourse) {
            return $this->sendError('لا يمكنك عرض أسئلة هذا الكورس', [], 403);
        }
    }

    $questions = $this->lessonQuestionService->getByLesson($lessonId);

    return $this->sendResponse(
        LessonQuestionResource::collection($questions),
        'تم جلب الأسئلة بنجاح'
    );
}

    // أسئلتي
    public function myQuestions(): JsonResponse
    {
        $questions = $this->lessonQuestionService->getMyQuestions(auth()->id());

        return $this->sendResponse(
            LessonQuestionResource::collection($questions),
            'تم جلب أسئلتك بنجاح'
        );
    }

    // طرح سؤال
    public function store(StoreLessonQuestionRequest $request): JsonResponse
    {
        $question = $this->lessonQuestionService->create(
            $request->validated(),
            auth()->id()
        );

        if (isset($question['error'])) {
            return $this->sendError($question['error'], [], 403);
        }

        return $this->sendResponse(
            new LessonQuestionResource($question),
            'تم طرح السؤال بنجاح',
            201
        );
    }

    // عرض سؤال
    public function show(int $id): JsonResponse
    {
        $question = $this->lessonQuestionService->getById($id);

        if (!$question) {
            return $this->sendError('السؤال غير موجود');
        }

        return $this->sendResponse(
            new LessonQuestionResource($question),
            'تم جلب السؤال بنجاح'
        );
    }

    // عرض الأسئلة المعلقة
    public function pending(): JsonResponse
    {
        $questions = $this->lessonQuestionService->getPending();

        return $this->sendResponse(
            LessonQuestionResource::collection($questions),
            'تم جلب الأسئلة المعلقة بنجاح'
        );
    }

    // الإجابة على سؤال
public function answer(AnswerLessonQuestionRequest $request, int $id): JsonResponse
{
    $question = $this->lessonQuestionService->getById($id);

    if (!$question) {
        return $this->sendError('السؤال غير موجود');
    }

    $user = auth()->user();

    // الطالب ممنوع من الإجابة
    if ($user->role === 'student') {
        return $this->sendError('غير مصرح لك بالإجابة', [], 403);
    }

    // المعلم يجاوب فقط على أسئلة كورساته
    if ($user->role === 'teacher') {
        $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
        $lessonCourse = $question->lesson?->course_id;
        
        $teacherCourse = $teacher 
            ? \App\Models\Course::where('teacher_id', $teacher->id)
                ->where('id', $lessonCourse)
                ->exists() 
            : false;

        if (!$teacherCourse) {
            return $this->sendError('لا يمكنك الإجابة على أسئلة هذا الكورس', [], 403);
        }
    }

    $question = $this->lessonQuestionService->answer(
        $id,
        $request->answer,
        auth()->id()
    );

    return $this->sendResponse(
        new LessonQuestionResource($question),
        'تمت الإجابة على السؤال بنجاح'
    );
}

    // إغلاق سؤال
    public function close(int $id): JsonResponse
    {
        $question = $this->lessonQuestionService->close($id);

        if (!$question) {
            return $this->sendError('السؤال غير موجود');
        }

        return $this->sendResponse(
            new LessonQuestionResource($question),
            'تم إغلاق السؤال بنجاح'
        );
    }

    // حذف سؤال
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->lessonQuestionService->delete($id);

        if (!$deleted) {
            return $this->sendError('السؤال غير موجود');
        }

        return $this->sendResponse(null, 'تم حذف السؤال بنجاح');
    }
}