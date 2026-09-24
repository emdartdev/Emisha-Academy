<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseLessonProgress extends Model
{
    use HasFactory;

    protected $table = 'course_lesson_progress';

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'course_id',
        'lesson_id',
        'is_completed',
        'last_playback_position',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'last_playback_position' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }
}
