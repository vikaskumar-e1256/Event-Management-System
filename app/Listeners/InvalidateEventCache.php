<?php

namespace App\Listeners;

use App\Events\EventChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class InvalidateEventCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventChanged $event)
    {
        $page = 1;

        $search = request()->get('search', '');
        $date = request()->get('date', '');
        $location = request()->get('location', '');

        while (Cache::has($this->getCacheKey($page, $search, $date, $location))) {
            Cache::forget($this->getCacheKey($page, $search, $date, $location));
            $page++;
        }
    }

    protected function getCacheKey($page, $search, $date, $location)
    {
        return "upcoming_events_page_{$page}_search_{$search}_date_{$date}_location_{$location}";
    }
}
