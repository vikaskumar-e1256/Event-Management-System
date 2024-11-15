<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');

        $eventsQuery = DB::table('events')
            ->where('events.user_id', Auth::id())
            ->when($startDate, function ($query, $startDate) {
                return $query->where('events.event_date', '>=', Carbon::parse($startDate)->format('Y-m-d'));
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->where('events.event_date', '<=', Carbon::parse($endDate)->format('Y-m-d'));
            });

        $totalEvents = $eventsQuery->count();

        $eventsStats = $eventsQuery
            ->join('ticket_types', 'events.id', '=', 'ticket_types.event_id')
            ->join('ticket_sales', 'ticket_types.id', '=', 'ticket_sales.ticket_type_id')
            ->join('payments', 'ticket_sales.id', '=', 'payments.ticket_sale_id')
            ->select(
                'events.id',
                'events.title',
                DB::raw('SUM(ticket_sales.quantity) as total_sales'),
                DB::raw('SUM(payments.amount) as total_revenue'), // Use payments for accurate revenue
                DB::raw('COUNT(DISTINCT ticket_sales.user_id) as total_attendees')
            )
            ->groupBy('events.id')
            ->get();

        return view('dashboard.index', compact('eventsStats', 'totalEvents'));
    }


}
