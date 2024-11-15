<?php

namespace Modules\Events\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Events\Database\Factories\EventFactory;
use Modules\Events\Events\EventChanged;
use Modules\Tickets\Models\TicketType;

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

    protected static function newFactory()
    {
        return EventFactory::new();
    }

}
