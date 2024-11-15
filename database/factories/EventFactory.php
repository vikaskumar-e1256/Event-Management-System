<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'event_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'location' => $this->faker->city,
            'user_id' => User::where('role', UserRole::ORGANIZER)->first()->id,
        ];
    }

    /**
     * Define the event with ticket types.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withTicketTypes()
    {
        return $this->afterCreating(function (Event $event) {
            $ticketTypes = [
                [
                    'name' => 'VIP',
                    'price' => $this->faker->numberBetween(100, 500),
                    'quantity' => $this->faker->numberBetween(10, 50),
                ],
                [
                    'name' => 'Early Bird',
                    'price' => $this->faker->numberBetween(20, 100),
                    'quantity' => $this->faker->numberBetween(20, 100),
                ],
                [
                    'name' => 'Regular',
                    'price' => $this->faker->numberBetween(10, 50),
                    'quantity' => $this->faker->numberBetween(50, 200),
                ],
            ];

            foreach ($ticketTypes as $ticketType) {
                TicketType::factory()->create([
                    'event_id' => $event->id,
                    'name' => $ticketType['name'],
                    'price' => $ticketType['price'],
                    'quantity' => $ticketType['quantity'],
                ]);
            }
        });
    }
}
