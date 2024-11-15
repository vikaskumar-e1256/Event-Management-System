@extends('layouts.app')
@section('title', 'All Events')

@section('content')
    <div class="container mt-4">
        <h1>All Events</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Tickets Left</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $event->title }}</td>
                        <td>
                            <textarea class="form-control" rows="3" readonly>{{ $event->description }}</textarea>
                        </td>
                        <td>{{ $event->event_date->format('Y-m-d') }}</td>
                        <td>{{ $event->location }}</td>
                        <td>{{ $event->total_tickets }}</td>
                        <td>
                            @can('update', $event)
                                <div class="btn-group">
                                    <a href="{{ route('events.edit', encrypt_data($event->id)) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('events.export', $event->id) }}" class="btn btn-info btn-sm">Export to Excel</a>
                                </div>
                            @endcan
                            @can('delete', $event)
                                <div class="btn-group ml-2">
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this event?')">Cancel</button>
                                    </form>
                                </div>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $events->links() }}
    </div>
@endsection
