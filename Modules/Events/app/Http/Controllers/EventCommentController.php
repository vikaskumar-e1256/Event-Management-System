<?php

namespace Modules\Events\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Events\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Events\Models\EventComment;

class EventCommentController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $eventId = decrypt_data($eventId);

        $request->validate(['comment' => 'required|string|max:500']);

        if (!is_numeric($eventId) || !Event::find($eventId)) {
            return response()->json(['error' => 'Invalid event ID'], 422);
        }
        $comment = EventComment::create([
            'event_id' => $eventId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => 'success',
            'comment' => [
                'user' => ['name' => Auth::user()->name],
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function index($eventId)
    {
        $eventId = decrypt_data($eventId);

        $comments = EventComment::where('event_id', $eventId)
            ->with('user:id,name')
            ->select('comment', 'user_id', 'created_at')
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'user' => ['name' => $comment->user->name],
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->toDateTimeString(),
                ];
            });

        return response()->json($comments);
    }
}
