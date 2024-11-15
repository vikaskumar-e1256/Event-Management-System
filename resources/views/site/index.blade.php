@extends('layouts.site')

@section('title', 'Home')

@section('content')
    <div class="container mt-4">
        
        <div class="d-flex justify-content-end">
            <a href="{{ route('site.event.upcoming.export') }}" class="btn btn-info">Export Upcoming Events</a>
        </div>

        <h1 class="text-center mb-4">Upcoming Events</h1>

        <form method="GET" action="{{ route('home') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or location" value="{{ request()->get('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request()->get('date') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="location" class="form-control" placeholder="Location" value="{{ request()->get('location') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Search</button>
                </div>
            </div>
        </form>

        <div class="row">
            @foreach ($upcomingEvents as $event)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <p class="text-muted">Date: {{ $event->event_date->format('F j, Y') }}</p>
                            <p class="text-muted">Location: {{ $event->location }}</p>
                            <a href="{{ route('site.events.show', encrypt_data($event->id)) }}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $upcomingEvents->links() }}
        </div>
    </div>
@endsection
