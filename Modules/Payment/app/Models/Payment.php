<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Tickets\Models\TicketSale;

class Payment extends Model
{
    protected $fillable = [
        'ticket_sale_id',
        'payment_method',
        'amount',
        'transaction_id',
        'status',
        'paid_at'
    ];

    public function ticketSale(): BelongsTo
    {
        return $this->belongsTo(TicketSale::class);
    }
}
