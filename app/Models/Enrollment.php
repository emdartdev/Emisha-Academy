<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Enrollment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'course_id',
        'batch_id',
        'order_id',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'course_id', 'batch_id', 'status', 'progress_percentage'])
            ->logOnlyDirty();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(CourseLessonProgress::class, 'enrollment_id');
    }

    /**
     * Recalculate real progress based on total published lessons in course.
     */
    public function recalculateProgress(): float
    {
        $publishedLessonCount = CourseLesson::whereHas('module', function ($q) {
            $q->where('course_id', $this->course_id)
                ->where('is_published', true);
        })->where('is_published', true)->count();

        if ($publishedLessonCount === 0) {
            $newProgress = 0.00;
        } else {
            $completedCount = $this->lessonProgress()
                ->where('is_completed', true)
                ->count();

            $newProgress = min(100.00, round(($completedCount / $publishedLessonCount) * 100, 2));
        }

        $isCompleted = $newProgress >= 100.00;

        $this->update([
            'progress_percentage' => $newProgress,
            'completed_at' => $isCompleted ? ($this->completed_at ?? now()) : null,
            'status' => $isCompleted ? 'completed' : ($this->status === 'completed' ? 'active' : $this->status),
        ]);

        return $newProgress;
    }
}
