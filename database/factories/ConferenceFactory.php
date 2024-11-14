<?php

namespace Database\Factories;

use App\Enums\Themes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conference>
 */
class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $allThemes = Themes::cases();
        $count = count($allThemes);
        $theme = $allThemes[fake()->numberBetween(0, $count - 1)];
        return [
            'title' => fake()->words(5, true),
            'description' => fake()->words(300, true),
            'theme' => $theme,
            'start_time' => fake()->date(),
            'end_time' => fake()->date(),
            'place_address' => fake()->address(),
            'price' => fake()->randomFloat(2, 0, 10000),
            'capacity' => fake()->numberBetween(1, 10000),
            'owner_id' => User::all()->random()->id,
        ];
    }
}
