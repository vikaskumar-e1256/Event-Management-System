<?php

namespace App\Models;

use App\Events\EventChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'user_id'
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(function () {
            EventChanged::dispatch();
        });

        static::deleted(function () {
            EventChanged::dispatch();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

}
