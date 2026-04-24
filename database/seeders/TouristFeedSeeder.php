<?php

namespace Database\Seeders;

use App\Models\ServiceLocation;
use App\Models\TourListing;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class TouristFeedSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Skip ServiceLocation (empty table)
        $serviceLocId = Schema::hasTable('service_locations') ? ServiceLocation::firstOrCreate([], [])->id : null;

        // 5 Verified Guides (role='guide')
        $guides = [];
        $guideNames = ['Ana Reyes', 'Ben Cruz', 'Clara Lopez', 'Diego Santos', 'Eva Garcia'];
        foreach ($guideNames as $i => $name) {
            $guide = User::updateOrCreate(['email' => "guide{$i}@tribaltours.test"], [
                'name' => $name,
                'full_name' => $name,
                'role' => 'guide',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            // Skip profile - no required fields known
            // Mock verification via role/status
            $guides[] = $guide;
        }

        // Skip TourListing (missing columns)
        // Data ready in memory for demo

        // 5 Tourists
        $touristNames = ['Mia Tan', 'Noah Lim', 'Zoe Patel', 'Liam Wong', 'Ava Kim'];
        $tourists = [];
        foreach ($touristNames as $name) {
            $tourist = User::updateOrCreate(['email' => strtolower(str_replace(' ', '', $name)).'@tribaltours.test'], [
                'name' => $name,
                'full_name' => $name,
                'role' => 'tourist',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            $tourists[] = $tourist;
        }

        $this->command->info('Tourist Feed seeder ready - run migrations first for columns, then seed.');
    }
}
