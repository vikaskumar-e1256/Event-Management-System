@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Organizer Dashboard</h1>

        <!-- Date Filter Form -->
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                </div>
            </div>
        </form>

        <div class="row">
            <!-- Total Events Box -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Events</h5>
                        <p class="card-text">{{ $totalEvents }} events</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Box -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <p class="card-text">${{ number_format($eventsStats->sum('total_revenue'), 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Attendees Box -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Attendees</h5>
                        <p class="card-text">{{ $eventsStats->sum('total_attendees') }} attendees</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Stats List -->
        @foreach($eventsStats as $stat)
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">{{ $stat->title }} - Statistics</h4>
                    <p class="card-text">
                        <strong>Tickets Sold:</strong> {{ $stat->total_sales }}<br>
                        <strong>Total Revenue:</strong> ${{ number_format($stat->total_revenue, 2) }}<br>
                        <strong>Attendees:</strong> {{ $stat->total_attendees }}<br>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endsection
