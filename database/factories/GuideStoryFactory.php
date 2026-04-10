<?php

namespace Database\Factories;

use App\Models\GuideStory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GuideStory>
 */
class GuideStoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guide_id' => User::factory()->create(['role' => 'guide'])->id,
            'image_path' => fake()->imageUrl(),
            'caption' => fake()->paragraph(),
            'expires_at' => now()->addDays(rand(1, 30)),
        ];
    }
}
