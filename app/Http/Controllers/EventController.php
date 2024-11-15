<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;

class EventController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $events = Event::withCount(['ticketTypes as total_tickets' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
                }])
                ->where('user_id', Auth::id())
                ->latest()
                ->simplePaginate($perPage);

        return view('events.index', ['events' => $events]);
    }


    public function create()
    {
        return view('events.create');
    }

    public function store(CreateEventRequest $request)
    {
        $this->authorize('create', Event::class);

        $validatedData = $request->validated();

        $event = Auth::user()->events()->create($validatedData);

        // Create ticket types
        $this->createTicketTypes($event, $validatedData['ticket_types']);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully!',
            'event' => $event
        ]);
    }

    public function edit($id)
    {
        $event = Event::with('ticketTypes')->findOrFail(decrypt_data($id));

        return view('events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $validatedData = $request->validated();

        $event->update($validatedData);

        // Update ticket types
        $event->ticketTypes()->delete();
        $this->createTicketTypes($event, $validatedData['ticket_types']);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully!',
            'event' => $event->load('ticketTypes')
        ]);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->back();
    }

    private function createTicketTypes(Event $event, array $ticketTypes)
    {
        foreach ($ticketTypes as $ticketTypeData) {
            $event->ticketTypes()->create($ticketTypeData);
        }
    }

}
