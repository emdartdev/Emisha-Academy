<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Course extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title_bn', 'title_en', 'slug', 'regular_price', 'sale_price', 'status'])
            ->logOnlyDirty();
    }

    protected $fillable = [
        'category_id',
        'instructor_id',
        'title_bn',
        'title_en',
        'slug',
        'subtitle_bn',
        'subtitle_en',
        'description_bn',
        'description_en',
        'thumbnail',
        'promo_video_url',
        'level',
        'format',
        'regular_price',
        'sale_price',
        'is_free',
        'duration_weeks',
        'total_hours',
        'total_classes',
        'total_projects',
        'features_bn',
        'features_en',
        'prerequisites_bn',
        'prerequisites_en',
        'target_audience_bn',
        'target_audience_en',
        'average_rating',
        'total_reviews',
        'enrolled_count',
        'is_featured',
        'is_popular',
        'status',
        'published_at',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'features_bn' => 'array',
        'features_en' => 'array',
        'prerequisites_bn' => 'array',
        'prerequisites_en' => 'array',
        'target_audience_bn' => 'array',
        'target_audience_en' => 'array',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class, 'course_instructors')
            ->withPivot(['role_bn', 'role_en', 'order_index'])
            ->withTimestamps()
            ->orderByPivot('order_index');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function activeBatch()
    {
        return $this->hasOne(Batch::class)->whereIn('status', ['upcoming', 'enrolling'])->latest('id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('order_index');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(CourseFaq::class)->orderBy('order_index');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class)->where('is_approved', true);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function notices(): BelongsToMany
    {
        return $this->belongsToMany(Notice::class, 'notice_courses');
    }
}
