<?php

namespace App\Services;

use App\Repositories\FavoriteRepository;

class FavoriteService
{
    public function __construct(
        private FavoriteRepository $favoriteRepository
    ) {}

    public function getUserFavorites(int $userId)
    {
        return $this->favoriteRepository->getUserFavorites($userId);
    }

    public function toggle(int $userId, int $courseId): array
    {
        $added = $this->favoriteRepository->toggle($userId, $courseId);

        return [
            'is_favorited' => $added,
            'message'      => $added
                ? 'تمت إضافة الكورس للمفضلة'
                : 'تمت إزالة الكورس من المفضلة',
        ];
    }

    public function updateNote(int $userId, int $courseId, ?string $note)
    {
        return $this->favoriteRepository->updateNote($userId, $courseId, $note);
    }

    public function updatePosition(int $userId, int $courseId, int $position)
    {
        return $this->favoriteRepository->updatePosition($userId, $courseId, $position);
    }

    public function isFavorited(int $userId, int $courseId): bool
    {
        return $this->favoriteRepository->isFavorited($userId, $courseId);
    }
}