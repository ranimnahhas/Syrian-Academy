<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'payment_code',
        'code_generated_at',
        'code_expires_at',
        'code_used_at',
        'status',
        'rejected_reason',
        'confirmed_by',
        'paid_at',
    ];

    protected $casts = [
        'code_generated_at' => 'datetime',
        'code_expires_at'   => 'datetime',
        'code_used_at'      => 'datetime',
        'paid_at'           => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}