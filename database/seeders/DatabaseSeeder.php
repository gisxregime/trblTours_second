<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'role' => 'tourist',
            'region' => null,
            'specialty' => null,
            'bio' => null,
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate([
            'email' => 'mara@example.com',
        ], [
            'name' => 'Mara Santos',
            'role' => 'guide',
            'region' => 'National Capital Region',
            'specialty' => 'Heritage walks',
            'bio' => 'Mara curates history-first city walks for travelers who want context, food stops, and architectural stories.',
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate([
            'email' => 'jasper@example.com',
        ], [
            'name' => 'Jasper Dela Cruz',
            'role' => 'guide',
            'region' => 'Central Visayas',
            'specialty' => 'Island hopping',
            'bio' => 'Jasper specializes in island routes, snorkeling stops, and small-group coastal itineraries.',
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate([
            'email' => 'leah@example.com',
        ], [
            'name' => 'Leah Mercado',
            'role' => 'guide',
            'region' => 'Davao Region',
            'specialty' => 'Mountain treks',
            'bio' => 'Leah leads guided treks with practical trail advice, local safety knowledge, and summit pacing for mixed skill levels.',
            'password' => Hash::make('password'),
        ]);

        $this->call(TourSeeder::class);
    }
}
