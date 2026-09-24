<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title_bn',
        'title_en',
        'content_bn',
        'content_en',
        'is_active',
    ];

    protected $casts = [
        'content_bn' => 'array',
        'content_en' => 'array',
        'is_active' => 'boolean',
    ];
}
