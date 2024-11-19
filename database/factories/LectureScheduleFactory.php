<?php

namespace Database\Factories;

use App\Models\Lecture;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LectureSchedule>
 */
class LectureScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::all()->random()->id,
            'lecture_id' => Lecture::all()->random()->id,
        ];
    }
}
