<?php

namespace Tests\Browser;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LandingPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_shows_the_landing_hero_and_featured_tours_from_the_database(): void
    {
        $mara = User::factory()->guide()->create([
            'name' => 'Mara Santos',
            'email' => 'mara@example.com',
            'region' => 'National Capital Region',
            'specialty' => 'Heritage walks',
            'bio' => 'City stories and food stops.',
        ]);

        $jasper = User::factory()->guide()->create([
            'name' => 'Jasper Dela Cruz',
            'email' => 'jasper@example.com',
            'region' => 'Central Visayas',
            'specialty' => 'Island hopping',
            'bio' => 'Island routes and snorkeling stops.',
        ]);

        Tour::factory()->create([
            'guide_id' => $mara->id,
            'title' => 'Manila Heritage Circuit',
            'region' => 'National Capital Region',
            'summary' => 'Historic center walk with cultural context and local food stops.',
            'is_featured' => true,
        ]);

        Tour::factory()->create([
            'guide_id' => $jasper->id,
            'title' => 'Cebu Reef and Island Day',
            'region' => 'Central Visayas',
            'summary' => 'Boat route with reef swim sites and island viewpoints.',
            'is_featured' => true,
        ]);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->assertSee('Where Every Journey Tells a Story')
                ->assertSee('Manila Heritage Circuit')
                ->assertSee('Cebu Reef and Island Day')
                ->type('q', 'Reef')
                ->select('region', 'Central Visayas')
                ->press('Explore Tours')
                ->assertSee('Cebu Reef and Island Day')
                ->assertDontSee('Manila Heritage Circuit');
        });
    }
}
