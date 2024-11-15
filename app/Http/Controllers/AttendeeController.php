<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendeeController extends Controller
{
    public function index()
    {
        $attendees = DB::table('users')
        ->join('ticket_sales', 'users.id', '=', 'ticket_sales.user_id')
        ->join('ticket_types', 'ticket_sales.ticket_type_id', '=', 'ticket_types.id')
        ->join('events', 'ticket_types.event_id', '=', 'events.id')
        ->where('events.user_id', Auth::id())
        ->select('users.id', 'users.name', 'users.email')
        ->distinct()
        ->simplePaginate(10);

        return view('attendees.index', compact('attendees'));
    }
}
