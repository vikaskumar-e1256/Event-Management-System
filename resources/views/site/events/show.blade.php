@extends('layouts.site')

@section('title', 'Event Details')

@section('content')
    <div class="container mt-4">
        <h1>{{ $event->title }}</h1>
        <p>{{ $event->description }}</p>
        <p><strong>Date:</strong> {{ $event->event_date->format('F j, Y') }}</p>
        <p><strong>Location:</strong> {{ $event->location }}</p>
        <p><strong>Total Available Tickets:</strong> {{ $event->total_tickets }}</p>

        <h3 class="mt-4">Purchase Tickets</h3>
        <form action="{{ route('payments.make') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="ticket_type" class="form-label">Select Ticket Type</label>
                <select id="ticket_type_id" name="ticket_type_id" class="form-select">
                    @foreach ($event->ticketTypes as $ticket)
                        <option value="{{ $ticket->id }}">{{ $ticket->name }} - ${{ $ticket->price }} ({{ $ticket->quantity }} left)</option>
                    @endforeach
                </select>
            </div>
            @if ($event->total_tickets > 0)
            <button type="submit" class="btn btn-success">Purchase</button>
            @endif
        </form>



        <h3 class="mt-4">Questions or Comments</h3>

        <div id="comments-list" class="mb-3">

        </div>

        @auth
        <form id="comment-form">
            @csrf
            <div class="mb-3">
                <textarea id="comment-content" class="form-control" rows="3" placeholder="Write your comment here..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Comment</button>
        </form>
        @else
        <p><a href="{{ route('login') }}">Login</a> to post a comment.</p>
        @endauth
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        const eventId = {{ $event->id }};
        const commentsList = $('#comments-list');

        // Load comments
        function loadComments() {
            $.get(`{{ route('comments.index', encrypt_data($event->id)) }}`, function (comments) {
                commentsList.empty();
                comments.forEach(comment => {
                    commentsList.append(`
                        <div class="card mb-2">
                            <div class="card-body">
                                <strong>${comment.user.name}</strong>
                                <p>${comment.comment}</p>
                                <small class="text-muted">${new Date(comment.created_at).toLocaleString()}</small>
                            </div>
                        </div>
                    `);
                });
            });
        }

        loadComments();

        $('#comment-form').submit(function (e) {
            e.preventDefault();

            const content = $('#comment-content').val();
            if (!content) return;

            $.post(`{{ route('comments.store', encrypt_data($event->id)) }}`, {
                _token: '{{ csrf_token() }}',
                comment: content
            }, function (response) {
                $('#comment-content').val('');
                loadComments();
            }).fail(function (error) {
                alert('Could not post comment. Please try again.');
            });
        });
    });
</script>
@endpush
