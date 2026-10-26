<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use App\Support\GymContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MapsLinkTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function payload(?string $mapsUrl): array
    {
        return [
            'phone' => '8700362410', 'whatsapp' => '8700362410', 'email' => 'hello@championclub.in', 'whatsapp_message' => 'Hi',
            'address' => ['street' => '12 Strength Avenue', 'locality' => 'Gurugram', 'region' => 'Haryana', 'maps_url' => $mapsUrl],
            'hours' => [['from' => 'Mo', 'to' => 'Sa', 'opens' => '06:00', 'closes' => '22:00']],
            'social' => ['instagram' => '', 'facebook' => ''],
            'years_running' => 8, 'about_title' => 'Heading', 'about_story' => 'Story.',
            'highlights' => config('gym.highlights'),
        ];
    }

    public function test_settings_page_has_google_maps_link_field(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/about')
            ->assertOk()
            ->assertSee('Google Maps link')
            ->assertSee('name="address[maps_url]"', false);
    }

    public function test_share_link_is_used_for_directions_and_map_falls_back_to_address(): void
    {
        $link = 'https://maps.app.goo.gl/AbCdEf123';

        $this->actingAs($this->admin(), 'admin')->put('/admin/about', $this->payload($link))->assertSessionHasNoErrors();

        $this->assertSame($link, Setting::where('key', 'address')->value('value')['maps_url']);

        $this->get('/')->assertSee('href="' . $link . '"', false)->assertSee('"hasMap":"' . $link . '"', false);
        $this->get('/contact')->assertSee(rawurlencode('12 Strength Avenue, Gurugram, Haryana') . '&amp;output=embed', false);
    }

    public function test_link_with_coordinates_pins_the_embedded_map(): void
    {
        $link = 'https://www.google.com/maps/place/ChampionClub/@28.4595,77.0266,17z/data=!3m1!4b1!4m6!3m5!1s0x0:0x0!8m2!3d28.4601!4d77.0271';

        $this->actingAs($this->admin(), 'admin')->put('/admin/about', $this->payload($link))->assertSessionHasNoErrors();

        $this->get('/contact')->assertSee('https://www.google.com/maps?q=28.4601%2C77.0271&amp;output=embed', false);
        $this->assertSame(['28.4595', '77.0266'], GymContact::coordinates('https://www.google.com/maps/@28.4595,77.0266,15z'));
        $this->assertSame(['28.5', '77.1'], GymContact::coordinates('https://maps.google.com/?q=28.5,77.1'));
        $this->assertNull(GymContact::coordinates('https://maps.app.goo.gl/AbCdEf123'));
    }

    public function test_non_google_links_are_rejected_and_empty_link_uses_address_search(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->put('/admin/about', $this->payload('https://example.com/my-gym'))
            ->assertSessionHasErrors(['address.maps_url' => 'Please paste a Google Maps link (google.com/maps or maps.app.goo.gl).']);

        $this->actingAs($admin, 'admin')->put('/admin/about', $this->payload(''))->assertSessionHasNoErrors();

        $this->assertNull(Setting::where('key', 'address')->value('value')['maps_url']);
        $this->get('/')->assertSee('https://www.google.com/maps/search/?api=1&amp;query=', false);
    }
}
