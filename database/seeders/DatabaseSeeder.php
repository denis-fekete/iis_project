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

        $adminConference = Conference::create([
            'title' => fake()->words(5, true),
            'description' => fake()->words(300, true),
            'theme' => fake()->words(10, true),
            'start_time' => fake()->date(),
            'end_time' => fake()->date(),
            'place_address' => fake()->address(),
            'price' => fake()->randomFloat(2, 0, 10000),
            'capacity' => fake()->numberBetween(1, 10000),
            'owner_id' => $admin->id,
            'poster' => 'https://marketplace.canva.com/EAGCxslOSOU/1/0/1131w/canva-blue-and-white-geometric-shapes-conference-poster-WytXZKj8OgA.jpg',
            'bank_account' => 'CZ1111000000111111111111',
        ]);

        for($i = 0; $i < 3; $i++) {
            Reservation::create([
                'is_confirmed' => fake()->boolean(),
                'user_id' => $admin->id,
                'conference_id' => $adminConference->id,
            ]);
        }

        for($i = 0; $i < 6; $i++) {
            Lecture::create([
                'title' => fake()->words(4, true),
                'poster' => fake()->words(10, true),
                'is_confirmed' => fake()->boolean(),
                'start_time' => fake()->date(),
                'end_time' => fake()->date(),
                'speaker_id' => $admin->id,
                'conference_id' => $adminConference->id,
                'room_id' => Room::all()->random()->id,
                'poster' => 'https://i.pinimg.com/236x/fb/2f/71/fb2f71ab6666351681955a5e518a70b1.jpg',
            ]);
        }
    }
}
