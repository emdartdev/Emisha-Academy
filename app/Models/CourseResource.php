<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'course_id',
        'title',
        'resource_type',
        'description',
        'file_path',
        'file_type',
        'file_size_bytes',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
