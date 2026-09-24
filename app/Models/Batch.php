<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'batch_number',
        'title_bn',
        'title_en',
        'start_date',
        'end_date',
        'enrollment_deadline',
        'class_days',
        'class_time',
        'seat_capacity',
        'enrolled_students',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_deadline' => 'date',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function getSeatsRemainingAttribute(): int
    {
        return max(0, $this->seat_capacity - $this->enrolled_students);
    }
}
