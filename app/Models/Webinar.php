<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_bn',
        'title_en',
        'slug',
        'subtitle_bn',
        'subtitle_en',
        'description_bn',
        'description_en',
        'thumbnail',
        'banner_image',
        'event_datetime',
        'duration_minutes',
        'platform',
        'meeting_link',
        'recording_url',
        'registration_fee',
        'is_free',
        'max_participants',
        'registered_count',
        'highlights_bn',
        'highlights_en',
        'agenda_bn',
        'agenda_en',
        'certificate_title_bn',
        'certificate_title_en',
        'certificate_note_bn',
        'certificate_note_en',
        'lab_upsell_title_bn',
        'lab_upsell_title_en',
        'lab_upsell_desc_bn',
        'lab_upsell_desc_en',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'event_datetime' => 'datetime',
        'registration_fee' => 'decimal:2',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'highlights_bn' => 'array',
        'highlights_en' => 'array',
        'agenda_bn' => 'array',
        'agenda_en' => 'array',
    ];

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'upcoming');
    }

    public function speakers(): HasMany
    {
        return $this->hasMany(WebinarSpeaker::class)->orderBy('order_index');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(WebinarRegistration::class);
    }
}
