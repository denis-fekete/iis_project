<?php

namespace Database\Factories;

use App\Models\Conference;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecture>
 */
class LectureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(4, true),
            'poster' => fake()->words(10, true),
            'description' => fake()->words(59, true),
            'is_confirmed' => fake()->boolean(),
            'start_time' => fake()->date(),
            'end_time' => fake()->date(),
            'speaker_id' => User::all()->random()->id,
            'conference_id' => Conference::all()->random()->id,
            'room_id' => Room::all()->random()->id,
        ];
    }
}
