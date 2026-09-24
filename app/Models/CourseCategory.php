<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_bn',
        'name_en',
        'slug',
        'track_title_bn',
        'track_title_en',
        'icon',
        'badge_text_bn',
        'badge_text_en',
        'description_bn',
        'description_en',
        'order_index',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
