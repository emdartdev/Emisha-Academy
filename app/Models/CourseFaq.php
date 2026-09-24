<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseFaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'question_bn',
        'question_en',
        'answer_bn',
        'answer_en',
        'order_index',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
