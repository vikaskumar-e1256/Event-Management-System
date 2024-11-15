<?php

namespace App\Mail;

use App\Models\TicketSale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketPurchaseConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticketSale;

    public function __construct(TicketSale $ticketSale)
    {
        $this->ticketSale = $ticketSale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ticket Purchase Confirmation')
            ->view('emails.ticket_purchase_confirmation');
    }
}
