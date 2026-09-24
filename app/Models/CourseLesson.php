<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title_bn',
        'title_en',
        'lesson_type',
        'duration',
        'video_provider',
        'video_url',
        'media_url',
        'short_description',
        'content',
        'quiz_data',
        'is_free_preview',
        'is_published',
        'is_locked',
        'notified_at',
        'order_index',
    ];

    protected $hidden = [
        'notified_at',
    ];

    protected $casts = [
        'quiz_data' => 'array',
        'notified_at' => 'datetime',
        'is_free_preview' => 'boolean',
        'is_published' => 'boolean',
        'is_locked' => 'boolean',
        'order_index' => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(CourseResource::class, 'lesson_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseLessonProgress::class, 'lesson_id');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(CourseQuizAttempt::class, 'lesson_id');
    }

    /**
     * Quiz payload safe for students: correct answers and explanations removed.
     */
    public function studentQuizData(): ?array
    {
        if (!is_array($this->quiz_data)) {
            return null;
        }

        $questions = collect($this->quiz_data['questions'] ?? [])->map(fn ($q, $i) => [
            'index' => $i,
            'question' => $q['question'] ?? '',
            'options' => array_values($q['options'] ?? []),
        ])->values()->all();

        return [
            'pass_percentage' => (int) ($this->quiz_data['pass_percentage'] ?? 60),
            'questions' => $questions,
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFreePreview($query)
    {
        return $query->where('is_free_preview', true);
    }
}
