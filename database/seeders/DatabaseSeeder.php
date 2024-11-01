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
        Lecture::factory(10)->create();
        Reservation::factory(30)->create();
        LectureSchedule::factory(30)->create();

        User::create([
            'name' => "Administrator",
            'surname' => "Administrator",
            'email' => "admin@admin.com",
            'password' => Hash::make('admin@admin.com'),
            'role' => RoleType::Admin->value
        ]);
    }
}
