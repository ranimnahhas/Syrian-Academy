<?php

namespace App\Services;

use App\Repositories\LessonRepository;

class LessonService
{
    public function __construct(
        private LessonRepository $lessonRepository,
        private BunnyStreamService $bunnyService
    ) {}

    public function getByCourse(int $courseId)
    {
        return $this->lessonRepository->getByCourse($courseId);
    }

    public function getById(int $id)
    {
        return $this->lessonRepository->find($id);
    }

    public function create(array $data)
    {
        // إذا في vimeo_id، نولد embed و path و duration تلقائياً
        if (!empty($data['vimeo_id'])) {
            $videoInfo = $this->bunnyService->getVideoInfo($data['vimeo_id']);

            $data['vimeo_embed'] = $data['vimeo_embed'] ?? $this->generateEmbedCode($data['vimeo_id']);
            $data['video_path'] = $data['video_path'] ?? 'https://iframe.mediadelivery.net/embed/' .
                config('services.bunny.library_id') . '/' . $data['vimeo_id'];
            $data['video_duration'] = $data['video_duration'] ?? $this->formatDuration($videoInfo['duration'] ?? null);
            $data['view_count'] = $data['view_count'] ?? ($videoInfo['views'] ?? 0);
        }

        return $this->lessonRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $lesson = $this->lessonRepository->find($id);

        if (!$lesson) {
            return null;
        }

        // إذا تغير vimeo_id، نحدث البيانات تلقائياً
        if (!empty($data['vimeo_id']) && $data['vimeo_id'] !== $lesson->vimeo_id) {
            $videoInfo = $this->bunnyService->getVideoInfo($data['vimeo_id']);

            $data['vimeo_embed'] = $this->generateEmbedCode($data['vimeo_id']);
            $data['video_path'] = 'https://iframe.mediadelivery.net/embed/' .
                config('services.bunny.library_id') . '/' . $data['vimeo_id'];
            $data['video_duration'] = $this->formatDuration($videoInfo['duration'] ?? null);
        }

        $lesson->update($data);
        return $lesson->fresh();
    }

    public function delete(int $id): ?bool
    {
        return $this->lessonRepository->delete($id);
    }

    public function incrementViews(int $id)
    {
        $this->lessonRepository->incrementViews($id);
    }

    private function generateEmbedCode(string $videoId): string
    {
        return '<iframe src="https://iframe.mediadelivery.net/embed/' .
            config('services.bunny.library_id') . '/' . $videoId .
            '" loading="lazy" style="border:none;width:100%;height:400px;"></iframe>';
    }

    private function formatDuration(?int $seconds): ?string
    {
        if (!$seconds) {
            return null;
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }
}