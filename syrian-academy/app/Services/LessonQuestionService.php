<?php

namespace App\Services;

use App\Repositories\LessonQuestionRepository;

class LessonQuestionService
{
    public function __construct(
        private LessonQuestionRepository $lessonQuestionRepository
    ) {}

    public function getByLesson(int $lessonId)
    {
        return $this->lessonQuestionRepository->getByLesson($lessonId);
    }

    public function getMyQuestions(int $userId)
    {
        return $this->lessonQuestionRepository->getMyQuestions($userId);
    }

    public function getPending()
    {
        return $this->lessonQuestionRepository->getPending();
    }

    public function getById(int $id)
    {
        return $this->lessonQuestionRepository->find($id);
    }

    public function create(array $data, int $userId)
{
    // تحقق من التسجيل في الكورس
    $lesson = \App\Models\Lesson::find($data['lesson_id']);
    
    if (!$lesson) {
        return ['error' => 'الدرس غير موجود'];
    }

    $isEnrolled = app(EnrollmentService::class)->isEnrolled($userId, $lesson->course_id);

    if (!$isEnrolled) {
        return ['error' => 'يجب أن تكون مسجلاً بالكورس لطرح سؤال'];
    }

    $data['user_id'] = $userId;
    $data['status'] = 'pending';
    $data['is_public'] = true;

    return $this->lessonQuestionRepository->create($data);
}

    public function answer(int $id, string $answer, int $answerBy)
    {
        $question = $this->lessonQuestionRepository->find($id);

        if (!$question) {
            return null;
        }

        $question->update([
            'answer'      => $answer,
            'answer_by'   => $answerBy,
            'answered_at' => now(),
            'status'      => 'answered',
        ]);

        return $question->fresh(['user', 'answerer', 'lesson']);
    }

    public function close(int $id)
    {
        $question = $this->lessonQuestionRepository->find($id);

        if (!$question) {
            return null;
        }

        $question->update(['status' => 'closed']);

        return $question->fresh();
    }

    public function delete(int $id): ?bool
    {
        return $this->lessonQuestionRepository->delete($id);
    }
}