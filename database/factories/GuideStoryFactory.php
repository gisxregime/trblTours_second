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
        $imagePath = fake()->imageUrl();

        return [
            'guide_id' => User::factory()->create(['role' => 'guide'])->id,
            'image_path' => $imagePath,
            'image_paths' => [$imagePath],
            'caption' => fake()->paragraph(),
            'content' => fake()->paragraph(),
            'likes_count' => 0,
            'liked_by' => [],
            'messages' => [],
            'expires_at' => now()->addDays(rand(1, 30)),
        ];
    }
}
