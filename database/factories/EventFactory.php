<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return 
        [
            'name' => fake()->catchPhrase(),
            'venue' => fake()->address(),
            'dateTime' => fake()->dateTime(),
            'status' => Event::PENDING,
            'poster' => null,
       
        ];
    
    }

    public function isApproved()
    {
        return $this->state(fn (array $attributes) => [
            'status' => Event::APPROVED,
        ]);
    }

    public function isRejected()
    {
        return $this->state(fn (array $attributes) => [
            'status' => Event::REJECTED,
        ]);
    }
}
