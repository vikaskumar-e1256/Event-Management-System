@extends('layouts.app')
@section('title', 'Attendees List')

@section('content')
    <div class="container mt-4">
        <h1>Attendees List</h1>

        <table class="table table-striped mb-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->id }}</td>
                        <td>{{ $attendee->name }}</td>
                        <td>{{ $attendee->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No attendees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $attendees->links() }}
        </div>
    </div>
@endsection
