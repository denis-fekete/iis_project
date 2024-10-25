<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\RoleType;
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
        // Create 10 random users
        // User::factory(10)->create();

        for($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'name' => fake()->name(),
                'surname' => fake()->lastName(),
                'email' => fake()->email(),
                'password' => Hash::make('password'),
                'role' => RoleType::User->value
            ]);
        }

        // Create one admin account
        User::factory()->create([
            'name' => 'Administrator',
            'surname' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'role' => RoleType::Admin->value
        ]);
    }
}
