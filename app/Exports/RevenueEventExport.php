<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\TicketType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueEventExport implements FromArray, WithHeadings, WithStyles
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function array(): array
    {
        $event = Event::findOrFail($this->eventId);
        $ticketTypes = TicketType::where('event_id', $this->eventId)->get();

        $data = [
            ['Event Details'],
            ['Event Title', $event->title],
            ['Event Date', $event->event_date->format('Y-m-d')],
            ['Location', $event->location],
            ['Description', $event->description],
            [],
            ['Ticket Type Details'],
            ['Ticket Type', 'Price', 'Quantity Sold', 'Total Revenue']
        ];

        foreach ($ticketTypes as $ticketType) {
            $totalSales = $ticketType->sales()->sum('quantity');
            $totalRevenue = $ticketType->sales()->sum('total_price');

            $data[] = [
                $ticketType->name,
                $ticketType->price,
                $totalSales,
                $totalRevenue
            ];
        }

        $data[] = [];
        $data[] = [
            'Total Tickets Sold',
            '',
            $ticketTypes->sum(fn($ticket) => $ticket->sales()->sum('quantity')),
            $ticketTypes->sum(fn($ticket) => $ticket->sales()->sum('total_price'))
        ];

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 16]], // Event Summary title
            6    => ['font' => ['bold' => true, 'size' => 16]], // Ticket type summary heading
            7    => ['font' => ['bold' => true]],               // Ticket table headers
            'A'  => ['alignment' => ['horizontal' => 'left']],  // Left-align main columns
            'C'  => ['alignment' => ['horizontal' => 'center']], // Center-align numeric data
            'D'  => ['alignment' => ['horizontal' => 'center']], // Center-align numeric data
            'E'  => ['alignment' => ['horizontal' => 'center']], // Center-align numeric data
        ];
    }
}
