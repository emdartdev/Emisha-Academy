<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbookDownload extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ebook_id',
        'user_id',
        'ip_address',
        'user_agent',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
