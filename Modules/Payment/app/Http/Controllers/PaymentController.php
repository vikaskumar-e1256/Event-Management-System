<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Mail\TicketPurchaseConfirmation;
use Modules\Tickets\Models\TicketType;

class PaymentController extends Controller
{
    public function makePayment(Request $request)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Lock the ticket type row for update
                $ticketType = TicketType::where('id', $request->ticket_type_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($ticketType->quantity < 1) {
                    throw new \Exception('Tickets are sold out for this type.');
                }

                $ticketType->decrement('quantity', 1);

                $ticketSale = $ticketType->sales()->create([
                    'user_id' => Auth::id(),
                    'quantity' => 1,
                    'total_price' => $ticketType->price,
                ]);

                $payment = $ticketSale->payments()->create([
                    'user_id' => Auth::id(),
                    'amount' => $ticketSale->total_price,
                    'status' => PaymentStatus::PENDING,
                    'payment_method' => 'credit_card',
                ]);

                // Payment could be success or failure
                $isSuccessful = rand(0, 1);

                if ($isSuccessful) {
                    $payment->update([
                        'status' => PaymentStatus::SUCCESS,
                        'transaction_id' => 'TXN' . strtoupper(uniqid()),
                        'paid_at' => now(),
                    ]);

                    Mail::to(Auth::user()->email)->queue(new TicketPurchaseConfirmation($ticketSale));
                } else {
                    $payment->update([
                        'status' => PaymentStatus::FAILED,
                    ]);

                    throw new \Exception('Payment failed. Try after sometime.');
                }
            });

            return redirect()->route('payments.result', ['status' => 'success']);
        } catch (\Exception $e) {
            return redirect()->route('payments.result', ['status' => 'failed'])
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function paymentResult(Request $request)
    {
        $status = $request->query('status');
        return view('site.payments.result', ['status' => $status]);
    }
}
