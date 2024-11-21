<?php

namespace Database\Factories;

use App\Enums\Themes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

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
        $start_time = fake()->dateTimeBetween('+1 week', '+4 weeks');
        $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));

        return [
            'title' => fake()->words(5, true),
            'description' => fake()->words(300, true),
            'theme' => $theme,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'place_address' => fake()->address(),
            'price' => fake()->randomFloat(2, 0, 10000),
            'capacity' => fake()->numberBetween(1, 10000),
            'owner_id' => User::all()->random()->id,
            'poster' => 'https://marketplace.canva.com/EAGCxslOSOU/1/0/1131w/canva-blue-and-white-geometric-shapes-conference-poster-WytXZKj8OgA.jpg',
        ];
    }
}
