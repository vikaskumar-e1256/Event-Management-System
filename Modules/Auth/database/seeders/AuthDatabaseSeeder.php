<?php

namespace Modules\Auth\Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
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
    }
}
