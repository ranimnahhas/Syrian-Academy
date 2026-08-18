<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'short_description',
        'content',
        'vimeo_id',
        'vimeo_embed',
        'video_path',
        'video_duration',
        'resource_path',
        'resource_type',
        'view_count',
    ];

    protected $casts = [
        'view_count' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}