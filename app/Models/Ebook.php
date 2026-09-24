<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title_bn',
        'title_en',
        'slug',
        'author_name_bn',
        'author_name_en',
        'author_designation_bn',
        'author_designation_en',
        'author_avatar',
        'edition_badge_bn',
        'edition_badge_en',
        'summary_bn',
        'summary_en',
        'description_bn',
        'description_en',
        'highlights_bn',
        'highlights_en',
        'chapters_bn',
        'chapters_en',
        'target_audience_bn',
        'target_audience_en',
        'lab_upsell_title_bn',
        'lab_upsell_title_en',
        'lab_upsell_desc_bn',
        'lab_upsell_desc_en',
        'lab_upsell_btn_text_bn',
        'lab_upsell_btn_text_en',
        'lab_upsell_btn_link',
        'cover_image',
        'preview_pdf_path',
        'file_path',
        'pages_count',
        'file_size',
        'regular_price',
        'sale_price',
        'is_free',
        'download_count',
        'rating',
        'reviews_count',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'pages_count' => 'integer',
        'download_count' => 'integer',
        'highlights_bn' => 'array',
        'highlights_en' => 'array',
        'chapters_bn' => 'array',
        'chapters_en' => 'array',
        'target_audience_bn' => 'array',
        'target_audience_en' => 'array',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EbookCategory::class, 'category_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(EbookDownload::class);
    }
}
