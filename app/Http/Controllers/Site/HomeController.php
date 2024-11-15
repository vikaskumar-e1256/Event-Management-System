<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Events\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $currentDate = now();
        $perPage = 20;

        $query = Event::where('event_date', '>=', $currentDate)
            ->orderBy('event_date', 'asc')
            ->select('id', 'title', 'event_date', 'location');

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('event_date', $request->date);
        }

        if ($request->has('location') && $request->location != '') {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $upcomingEvents = Cache::remember("upcoming_events_page_" . request('page', 1) . '_search_' . $request->search . '_date_' . $request->date . '_location_' . $request->location, now()->addMinutes(10), function () use ($query, $perPage) {
            return $query->simplePaginate($perPage);
        });

        return view('site.index', compact('upcomingEvents'));
    }


    public function show($id)
    {
        $event = Event::withCount(['ticketTypes as total_tickets' => function ($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])->findOrFail(decrypt_data($id));

        return view('site.events.show', compact('event'));
    }

}
