<?php

namespace Database\Factories;

use App\Models\Conference;
use App\Models\User;
use App\Services\ConferenceService;
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
        $conference = Conference::all()->random();
        $capacity_left = ConferenceService ::capacityLeft($conference->id);
        if($capacity_left <= 1) {
            $conference->capacity += 10;
            $conference->save();
            $capacity_left = ConferenceService ::capacityLeft($conference->id);
        }
        return [
            'is_confirmed' => fake()->boolean(),
            'user_id' => User::all()->random()->id,
            'conference_id' => $conference->id,
            'number_of_people' => fake()->numberBetween(1, $capacity_left / 5),
        ];
    }
}
