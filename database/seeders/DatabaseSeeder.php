<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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
            'full_name' => 'Test User',
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
            'full_name' => 'Mara Santos',
            'role' => 'tour_guide',
            'region' => 'National Capital Region',
            'specialty' => 'Heritage walks',
            'bio' => 'Mara curates history-first city walks for travelers who want context, food stops, and architectural stories.',
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate([
            'email' => 'jasper@example.com',
        ], [
            'name' => 'Jasper Dela Cruz',
            'full_name' => 'Jasper Dela Cruz',
            'role' => 'tour_guide',
            'region' => 'Central Visayas',
            'specialty' => 'Island hopping',
            'bio' => 'Jasper specializes in island routes, snorkeling stops, and small-group coastal itineraries.',
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate([
            'email' => 'leah@example.com',
        ], [
            'name' => 'Leah Mercado',
            'full_name' => 'Leah Mercado',
            'role' => 'tour_guide',
            'region' => 'Davao Region',
            'specialty' => 'Mountain treks',
            'bio' => 'Leah leads guided treks with practical trail advice, local safety knowledge, and summit pacing for mixed skill levels.',
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate([
            'email' => 'admin@tribaltours.test',
        ], [
            'name' => 'System Admin',
            'full_name' => 'System Admin',
            'role' => 'admin',
            'status' => 'active',
            'region' => null,
            'specialty' => null,
            'bio' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('admin12345'),
        ]);

        if (Schema::hasTable('tours') && Schema::hasColumn('tours', 'title')) {
            $this->call(TourSeeder::class);
        }
    }
}
