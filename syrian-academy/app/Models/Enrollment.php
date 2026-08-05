<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_request_id',
        'enrolled_at',
        'is_active',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollmentRequest(): BelongsTo
    {
        return $this->belongsTo(EnrollmentRequest::class);
    }
}