<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsTestimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name_bn',
        'student_name_en',
        'student_role_bn',
        'student_role_en',
        'avatar',
        'course_name_bn',
        'course_name_en',
        'quote_bn',
        'quote_en',
        'rating',
        'video_review_url',
        'order_index',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
