<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_bn',
        'title_en',
        'subtitle_bn',
        'subtitle_en',
        'image_url',
        'button_text_bn',
        'button_text_en',
        'button_link',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
