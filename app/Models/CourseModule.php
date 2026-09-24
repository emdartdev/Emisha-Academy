<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title_bn',
        'title_en',
        'summary_bn',
        'summary_en',
        'order_index',
        'is_published',
        'is_locked',
        'notified_at',
    ];

    protected $hidden = [
        'notified_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'is_published' => 'boolean',
        'is_locked' => 'boolean',
        'order_index' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class, 'module_id')->orderBy('order_index');
    }

    public function publishedLessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class, 'module_id')
            ->where('is_published', true)
            ->orderBy('order_index');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
