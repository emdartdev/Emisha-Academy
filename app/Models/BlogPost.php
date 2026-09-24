<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'author_id',
        'author_name_bn',
        'author_name_en',
        'author_designation_bn',
        'author_designation_en',
        'author_avatar',
        'title_bn',
        'title_en',
        'slug',
        'summary_bn',
        'summary_en',
        'content_bn',
        'content_en',
        'thumbnail',
        'reading_time',
        'views_count',
        'tags',
        'is_featured',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'post_id')->where('is_approved', true);
    }
}
