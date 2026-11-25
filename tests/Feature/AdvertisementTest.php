<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_active_advertisements_on_all_placements(): void
    {
        $billboard = Advertisement::factory()->create([
            'title' => 'Shree Mahakal Grand Dharamshala',
            'placement' => 'billboard',
            'is_active' => true,
        ]);

        $sponsorCard = Advertisement::factory()->create([
            'title' => 'Famous Ujjain Namkeen Corner',
            'placement' => 'sponsor_card',
            'is_active' => true,
        ]);

        $inFeed = Advertisement::factory()->create([
            'title' => 'Avanti Pure Gold Jewellers',
            'placement' => 'in_feed',
            'is_active' => true,
        ]);

        $leftRail = Advertisement::factory()->create([
            'title' => 'Mahakal 24x7 Cab Travel',
            'placement' => 'left_rail',
            'is_active' => true,
        ]);

        $rightRail = Advertisement::factory()->create([
            'title' => 'Vedic Puja Darshan Guide',
            'placement' => 'right_rail',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Shree Mahakal Grand Dharamshala');
        $response->assertSee('Famous Ujjain Namkeen Corner');
        $response->assertSee('Avanti Pure Gold Jewellers');
        $response->assertSee('Mahakal 24x7 Cab Travel');
        $response->assertSee('Vedic Puja Darshan Guide');
    }

    public function test_homepage_does_not_display_inactive_advertisements(): void
    {
        Advertisement::factory()->create([
            'title' => 'Hidden Inactive Promo Banner',
            'placement' => 'billboard',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Hidden Inactive Promo Banner');
    }

    public function test_homepage_does_not_display_expired_advertisements(): void
    {
        Advertisement::factory()->create([
            'title' => 'Expired Last Month Sale',
            'placement' => 'billboard',
            'is_active' => true,
            'ends_at' => now()->subDay(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Expired Last Month Sale');
    }

    public function test_admin_can_access_advertisements_in_filament_panel(): void
    {
        Role::create(['name' => 'Admin']);

        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $advertisement = Advertisement::factory()->create([
            'title' => 'Manageable Portal Ad',
            'placement' => 'billboard',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/portal/advertisements');

        $response->assertStatus(200);
        $response->assertSee('Manageable Portal Ad');
    }

    public function test_advertisement_scopes_filter_correctly(): void
    {
        $activeBillboard = Advertisement::factory()->create([
            'title' => 'Active Billboard',
            'placement' => 'billboard',
            'is_active' => true,
        ]);

        $inactiveBillboard = Advertisement::factory()->create([
            'title' => 'Inactive Billboard',
            'placement' => 'billboard',
            'is_active' => false,
        ]);

        $activeSponsor = Advertisement::factory()->create([
            'title' => 'Active Sponsor',
            'placement' => 'sponsor_card',
            'is_active' => true,
        ]);

        $activeAds = Advertisement::active()->get();
        $this->assertTrue($activeAds->contains($activeBillboard));
        $this->assertFalse($activeAds->contains($inactiveBillboard));
        $this->assertTrue($activeAds->contains($activeSponsor));

        $billboards = Advertisement::placement('billboard')->get();
        $this->assertTrue($billboards->contains($activeBillboard));
        $this->assertTrue($billboards->contains($inactiveBillboard));
        $this->assertFalse($billboards->contains($activeSponsor));
    }
}
