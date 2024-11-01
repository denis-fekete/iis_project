<?php

namespace Database\Factories;

use App\Models\Conference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_confirmed' => fake()->boolean(),
            'user_id' => User::all()->random()->id,
            'conference_id' => Conference::all()->random()->id,
        ];
    }
}
