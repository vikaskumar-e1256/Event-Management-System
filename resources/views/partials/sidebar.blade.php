<div class="col-md-3 col-lg-2 p-3 bg-light">
    <h4 class="text-center">Event Manager</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                Dashboard
            </a>
        </li>
        @can('create', App\Models\Event::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('events.create') }}">
                Create Event
            </a>
        </li>
        @endcan

        <li class="nav-item">
            <a class="nav-link" href="{{ route('events.index') }}">
                View Events
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('attendees.index') }}">
                View Attendees
            </a>
        </li>

        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link btn btn-link">Logout</button>
            </form>
        </li>
    </ul>
</div>
