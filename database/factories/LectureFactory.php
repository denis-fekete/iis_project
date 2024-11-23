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
        $rooms = $conference->rooms;
        $roomId = $rooms->isNotEmpty() ? $rooms->random()->id : null;

        if($roomId == null) {
            Room::factory(1)->create([
                'conference_id' => $conference->id,
            ]);
            $roomId = $rooms->isNotEmpty() ? $rooms->random()->id : null;
        }

        $start_time = fake()->dateTimeBetween($conference->start_time, $conference->end_time);
        $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+6 hours'));
        $title = fake()->sentence();

        return [
            'title' => $title,
            'description' => fake()->paragraphs(3, true),
            'is_confirmed' => fake()->boolean(),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'speaker_id' => User::all()->random()->id,
            'conference_id' => $conference->id,
            'room_id' => $roomId,
            'poster' => 'https://picsum.photos/seed/' . $title . '/1200/400',
        ];
    }
}
