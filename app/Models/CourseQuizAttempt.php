<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseQuizAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'enrollment_id',
        'lesson_id',
        'answers',
        'score',
        'total',
        'percentage',
        'passed',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'integer',
        'total' => 'integer',
        'percentage' => 'float',
        'passed' => 'boolean',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }
}
