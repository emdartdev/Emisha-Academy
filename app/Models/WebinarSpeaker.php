<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebinarSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'name_bn',
        'name_en',
        'designation_bn',
        'designation_en',
        'organization',
        'bio_bn',
        'bio_en',
        'avatar',
        'linkedin_url',
        'order_index',
    ];

    public function webinar(): BelongsTo
    {
        return $this->belongsTo(Webinar::class);
    }
}
