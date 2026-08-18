<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonQuestion extends Model
{
    protected $fillable = [
        'lesson_id',
        'user_id',
        'question',
        'answer',
        'answer_by',
        'answered_at',
        'status',
        'is_public',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'is_public'   => 'boolean',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answer_by');
    }
}