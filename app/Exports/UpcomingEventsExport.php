<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\Events\Models\Event;

class UpcomingEventsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithChunkReading
{
    public function query()
    {
        return Event::where('event_date', '>=', now())
            ->with('ticketTypes')
            ->select('id', 'title', 'location', 'event_date');
    }

    public function headings(): array
    {
        return [
            'Event Name',
            'Location',
            'Event Date',
            'Ticket Details',
        ];
    }

    public function map($event): array
    {
        $ticketDetails = $event->ticketTypes->map(function ($ticketType) {
            return "{$ticketType->name}: {$ticketType->quantity} tickets";
        })->join(", ");

        return [
            $event->title,
            $event->location,
            $event->event_date->format('Y-m-d'),
            $ticketDetails,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
