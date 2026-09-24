<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Notice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'notice_type',
        'priority',
        'visibility',
        'status',
        'created_by',
        'updated_by',
        'publish_at',
        'expires_at',
        'published_at',
        'notified_at',
    ];

    protected $hidden = [
        'notified_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'notice_type', 'priority', 'visibility', 'status'])
            ->logOnlyDirty();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'notice_courses')
            ->withTimestamps();
    }

    public function reads(): HasMany
    {
        return $this->hasMany(NoticeRead::class, 'notice_id');
    }

    /**
     * Scope: Currently active & published notices.
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query->where('status', 'published')
            ->where(function ($q) use ($now) {
                $q->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            });
    }

    /**
     * Scope: Eligible for a specific student.
     */
    public function scopeForStudent(Builder $query, User $user): Builder
    {
        // Get active course IDs for this student
        $enrolledCourseIds = Enrollment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->pluck('course_id')
            ->toArray();

        return $query->active()->where(function ($q) use ($enrolledCourseIds) {
            // 1. Notices targeted to all students
            $q->where('visibility', 'all_students')
                // 2. Or notices targeted to one or more of the student's enrolled courses
                ->orWhere(function ($courseQuery) use ($enrolledCourseIds) {
                    $courseQuery->whereIn('visibility', ['course', 'multiple_courses'])
                        ->whereHas('courses', function ($c) use ($enrolledCourseIds) {
                            $c->whereIn('courses.id', $enrolledCourseIds);
                        });
                });
        });
    }

    /**
     * Check if a specific user has read this notice.
     */
    public function isReadBy(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }
}
