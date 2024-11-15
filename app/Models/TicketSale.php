<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketSale extends Model
{
    protected $fillable = [
        'ticket_type_id',
        'user_id',
        'quantity',
        'total_price',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
