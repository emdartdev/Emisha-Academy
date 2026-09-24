<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'phone',
                'whatsapp_number',
                'email',
                'lead_type',
                'source_content_type',
                'source_content_title',
                'status',
                'priority',
                'assigned_to',
                'employee_id',
                'source',
            ])
            ->logOnlyDirty();
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp_number',
        'lead_type',
        'source_content_type',
        'source_content_id',
        'source_content_title',
        'source_content_slug',
        'source_url',
        'course_id',
        'interested_topic',
        'source',
        'status',
        'priority',
        'assigned_to',
        'employee_id',
        'accepted_at',
        'accepted_by',
        'next_follow_up_at',
        'last_contacted_at',
        'notes',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'referrer_url',
    ];

    protected $casts = [
        'next_follow_up_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Employee (admin-managed name) handling this lead
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Staff login account that accepted the lead
     */
    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    /**
     * Primary Course relationship (if lead originated from a Course)
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'source_content_id')
            ->when(!$this->source_content_id && $this->course_id, function ($q) {
                return $this->belongsTo(Course::class, 'course_id');
            });
    }

    /**
     * Webinar relationship (if lead originated from a Webinar)
     */
    public function webinar(): BelongsTo
    {
        return $this->belongsTo(Webinar::class, 'source_content_id');
    }

    /**
     * Ebook relationship (if lead originated from an Ebook)
     */
    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class, 'source_content_id');
    }

    /**
     * Assigned Counselor / Worker
     */
    public function assignedCounselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Threaded Lead Notes
     */
    public function leadNotes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest('id');
    }

    /**
     * Timeline activities log
     */
    public function leadActivities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest('id');
    }

    // --- Query Scopes ---

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('lead_type', $type);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeFollowUpDue(Builder $query): Builder
    {
        return $query->whereNotNull('next_follow_up_at')
            ->whereDate('next_follow_up_at', '<=', now()->toDateString());
    }
}
