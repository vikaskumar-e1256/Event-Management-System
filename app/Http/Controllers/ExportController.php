<?php

namespace App\Http\Controllers;

use App\Exports\RevenueEventExport;
use Illuminate\Http\Request;
use App\Exports\UpcomingEventsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportUpcomingEvents()
    {
        return Excel::download(new UpcomingEventsExport, 'upcoming_events.xlsx');
    }

    public function exportEventWithRevenueData($eventId)
    {
        return Excel::download(new RevenueEventExport($eventId), 'events.xlsx');
    }
}
