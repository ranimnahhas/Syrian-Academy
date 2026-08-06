<?php

namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;

class FavoriteRepository extends BaseRepository
{
    public function __construct(Favorite $favorite)
    {
        parent::__construct($favorite);
    }

    public function getUserFavorites(int $userId): Collection
    {
        return $this->model
            ->with(['course.category', 'course.teacher.user'])
            ->where('user_id', $userId)
            ->orderBy('position')
            ->latest()
            ->get();
    }

    public function findByUserAndCourse(int $userId, int $courseId): ?Favorite
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function toggle(int $userId, int $courseId): bool
    {
        $favorite = $this->findByUserAndCourse($userId, $courseId);

        if ($favorite) {
            $removedPosition = $favorite->position;
            $favorite->delete();

             // إعادة ترتيب العناصر بعد الحذف
             $this->model
                ->where('user_id', $userId)
                ->where('position', '>', $removedPosition)
                ->decrement('position');

             return false;
         }

         $maxPosition = $this->model
            ->where('user_id', $userId)
            ->max('position') ?? 0;

        $this->model->create([
            'user_id'   => $userId,
            'course_id' => $courseId,
            'position'  => $maxPosition + 1,
        ]);

        return true;
    }

    public function updateNote(int $userId, int $courseId, ?string $note): ?Favorite
    {
        $favorite = $this->findByUserAndCourse($userId, $courseId);

        if (!$favorite) {
            return null;
        }

        $favorite->update(['note' => $note]);

        return $favorite->fresh('course');
    }

    public function updatePosition(int $userId, int $courseId, int $position): ?Favorite
    {
       $favorite = $this->findByUserAndCourse($userId, $courseId);
 
       if (!$favorite) {
            return null;
        }

        $oldPosition = $favorite->position;

        // إذا نفس المركز، ما نعمل شي
        if ($oldPosition === $position) {
            return $favorite->fresh('course');
     }

        // تعديل ترتيب العناصر الأخرى
        if ($position < $oldPosition) {
            // بنحرك العناصر اللي بين المركزين لفوق
             $this->model
                ->where('user_id', $userId)
                ->where('position', '>=', $position)
                ->where('position', '<', $oldPosition)
                ->where('id', '!=', $favorite->id)
                ->increment('position');
         } else {
            // بنحرك العناصر اللي بين المركزين لتحت
            $this->model
                ->where('user_id', $userId)
                ->where('position', '>', $oldPosition)
                ->where('position', '<=', $position)
                ->where('id', '!=', $favorite->id)
                ->decrement('position');
         }

         // تحديث العنصر المطلوب
         $favorite->update(['position' => $position]);

         return $favorite->fresh('course');
    }

    public function isFavorited(int $userId, int $courseId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }
}