<?php

namespace Modules\Tickets\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Events\Models\Event;
use Illuminate\Support\Facades\DB;

class TicketTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all events
        $events = Event::all();

        foreach ($events as $event) {
            // Create multiple ticket types for each event
            DB::table('ticket_types')->insert([
                [
                    'event_id' => $event->id,
                    'name' => 'VIP',
                    'price' => $faker->numberBetween(50, 200),
                    'quantity' => $faker->numberBetween(10, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'event_id' => $event->id,
                    'name' => 'Regular',
                    'price' => $faker->numberBetween(20, 100),
                    'quantity' => $faker->numberBetween(50, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'event_id' => $event->id,
                    'name' => 'Early Bird',
                    'price' => $faker->numberBetween(10, 50),
                    'quantity' => $faker->numberBetween(100, 200),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
