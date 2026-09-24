<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_bn',
        'name_en',
        'title_bn',
        'title_en',
        'bio_bn',
        'bio_en',
        'avatar',
        'experience_years',
        'organization',
        'linkedin_url',
        'facebook_url',
        'github_url',
        'rating',
        'total_students',
        'is_featured',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function assignedCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_instructors')
            ->withPivot(['role_bn', 'role_en', 'order_index'])
            ->withTimestamps();
    }
}
