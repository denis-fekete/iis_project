<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\RoleType;
use App\Models\Conference;
use App\Models\Lecture;
use App\Models\LectureSchedule;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Conference::factory(10)->create();
        Room::factory(5)->create();
        Lecture::factory(50)->create();
        Reservation::factory(30)->create();
        LectureSchedule::factory(30)->create();

        $this->setupAdmin();
    }

    private function setupAdmin() {
        $admin = User::create([
            'name' => "Administrator",
            'surname' => "Administrator",
            'email' => "admin@admin.com",
            'password' => Hash::make('admin@admin.com'),
            'role' => RoleType::Admin->value
        ]);

        $start_time = fake()->dateTimeBetween('+2 months', '+12 months');
        $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));

        $adminConference = Conference::create([
            'title' => fake()->words(5, true),
            'description' => fake()->words(300, true),
            'theme' => fake()->words(10, true),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'place_address' => fake()->address(),
            'price' => fake()->randomFloat(2, 0, 10000),
            'capacity' => fake()->numberBetween(1, 10000),
            'owner_id' => $admin->id,
            'poster' => 'https://i.ibb.co/dfXSZTr/ca8dbc8b-4616-43af-b058-8b3ca681bd6a.webp',
            'bank_account' => 'CZ1111000000111111111111',
        ]);

        for($i = 0; $i < 3; $i++) {
            Reservation::create([
                'is_confirmed' => fake()->boolean(),
                'user_id' => $admin->id,
                'conference_id' => $adminConference->id,
            ]);
        }

        for($i = 0; $i < 2; $i++) {
            $start_time = fake()->dateTimeBetween($adminConference->start_time, $adminConference->end_time);
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));

            Lecture::create([
                'title' => fake()->words(4, true),
                'poster' => fake()->words(10, true),
                'is_confirmed' => fake()->boolean(),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'speaker_id' => $admin->id,
                'conference_id' => $adminConference->id,
                'room_id' => Room::all()->random()->id,
                'poster' => 'https://i.ibb.co/fQwgJqW/fb3acb40-b79c-44ee-a6ef-7573ad349d36.webp',
            ]);
        }
    }
}
