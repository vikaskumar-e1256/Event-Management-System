<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Organizer',
            'email' => 'org@gmail.com',
            'role' => UserRole::ORGANIZER
        ]);
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role' => UserRole::ATTENDEE
        ]);

        Event::factory(100)->withTicketTypes()->create();

    }
}
