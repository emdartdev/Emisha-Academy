<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'designation',
        'phone',
        'email',
        'is_active',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'designation', 'phone', 'email', 'is_active'])
            ->logOnlyDirty();
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
