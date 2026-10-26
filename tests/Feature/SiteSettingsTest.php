<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function payload(array $overrides = []): array
    {
        return array_replace_recursive([
            'phone' => '9811122233',
            'whatsapp' => '9811144455',
            'email' => 'team@newgym.in',
            'whatsapp_message' => 'Hello from the website',
            'address' => ['street' => '45 Fitness Road, Sector 50', 'locality' => 'Noida', 'region' => 'Uttar Pradesh'],
            'hours' => [
                ['from' => 'Mo', 'to' => 'Fr', 'opens' => '05:30', 'closes' => '23:00'],
                ['from' => 'Su', 'to' => 'Su', 'opens' => '08:00', 'closes' => '12:00'],
            ],
            'social' => ['instagram' => 'https://instagram.com/newgym', 'facebook' => ''],
            'years_running' => 10,
            'about_title' => 'Coached, not just supervised.',
            'about_story' => "First paragraph about us.\n\nSecond paragraph about us.",
            'highlights' => [
                ['value' => 25000, 'suffix' => '', 'label' => 'Sq. ft. floor', 'icon' => 'area'],
                ['value' => 15, 'suffix' => '', 'label' => 'Coaches', 'icon' => 'medal'],
                ['value' => 900, 'suffix' => '+', 'label' => 'Members', 'icon' => 'users'],
                ['value' => 7, 'suffix' => '', 'label' => 'Days open', 'icon' => 'calendar'],
            ],
        ], $overrides);
    }

    public function test_guest_cannot_open_about_settings(): void
    {
        $this->get('/admin/about')->assertRedirect(route('admin.login'));
        $this->put('/admin/about', [])->assertRedirect(route('admin.login'));
    }

    public function test_settings_page_shows_current_details(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/about')
            ->assertOk()
            ->assertSee('About &amp; Contact', false)
            ->assertSee('value="8700362410"', false)
            ->assertSee(config('gym.address.street'))
            ->assertSee('value="06:00"', false)
            ->assertSee('For 8+ years');
    }

    public function test_admin_changes_show_across_the_website(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->put('/admin/about', $this->payload())
            ->assertRedirect(route('admin.site.edit'))
            ->assertSessionHasNoErrors();

        $this->assertSame('9811122233', Setting::where('key', 'phone')->value('value'));

        $home = $this->get('/')->assertOk();
        $home->assertSee('href="tel:09811122233"', false)
            ->assertSee('https://wa.me/919811144455', false)
            ->assertSee('Mon – Fri 5:30 AM – 11:00 PM')
            ->assertSee('Sunday 8:00 AM – 12:00 PM')
            ->assertSee('Noida, Uttar Pradesh')
            ->assertSee('team@newgym.in')
            ->assertSee('https://instagram.com/newgym', false)
            ->assertSee('data-count="25000"', false)
            ->assertSee('"openingHours":["Mo-Fr 05:30-23:00","Su 08:00-12:00"]', false);

        $this->get('/contact')->assertSee(rawurlencode('45 Fitness Road, Sector 50, Noida, Uttar Pradesh'), false);

        $this->get('/about')
            ->assertSee('First paragraph about us.')
            ->assertSee('Second paragraph about us.')
            ->assertSee('10+');
    }

    public function test_achievements_are_no_longer_part_of_settings(): void
    {
        $this->actingAs($this->admin(), 'admin')->get('/admin/about')->assertOk()->assertDontSee('Achievements');
        $this->get('/about')->assertOk()->assertDontSee('Milestones along the way.');
    }

    public function test_invalid_details_are_rejected(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->put('/admin/about', $this->payload([
                'phone' => '12345',
                'email' => 'not-an-email',
                'hours' => [0 => ['from' => 'Mo', 'to' => 'Sa', 'opens' => '22:00', 'closes' => '06:00']],
            ]))
            ->assertSessionHasErrors(['phone', 'email', 'hours.0.closes']);

        $this->assertSame(0, Setting::count());
    }

    public function test_navbar_services_dropdown_lists_active_services(): void
    {
        Service::create(['title' => 'Kids Boxing', 'slug' => 'kids-boxing', 'icon' => 'boxing', 'summary' => 'x', 'description' => 'y', 'is_active' => true, 'display_order' => 1]);
        Service::create(['title' => 'Hidden Class', 'slug' => 'hidden-class', 'icon' => 'yoga', 'summary' => 'x', 'description' => 'y', 'is_active' => false, 'display_order' => 2]);

        $this->get('/about')
            ->assertSee('nav-drop-panel', false)
            ->assertSee(route('gym.service', 'kids-boxing'))
            ->assertSee('Kids Boxing')
            ->assertDontSee('Hidden Class');
    }

    public function test_navbar_has_plain_services_link_when_no_services(): void
    {
        $this->get('/')->assertDontSee('nav-drop-panel', false)->assertSee(route('gym.services'));
    }
}
