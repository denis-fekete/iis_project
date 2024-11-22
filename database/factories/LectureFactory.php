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
        $conference = Conference::all()->random();
        $start_time = fake()->dateTimeBetween($conference->start_time, $conference->end_time);
        $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));

        return [
            'title' => fake()->words(4, true),
            'poster' => fake()->words(10, true),
            'description' => fake()->words(59, true),
            'is_confirmed' => fake()->boolean(),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'speaker_id' => User::all()->random()->id,
            'conference_id' => $conference->id,
            'room_id' => Room::all()->random()->id,
            'poster' => 'https://i.ibb.co/fQwgJqW/fb3acb40-b79c-44ee-a6ef-7573ad349d36.webp',
        ];
    }
}
