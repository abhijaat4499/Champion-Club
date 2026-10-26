<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Trainer;
use App\Support\GymContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymContactCtaTest extends TestCase
{
    use RefreshDatabase;

    private const WA = 'https://wa.me/918700362410?text=Hi%2C%20I%20want%20to%20know%20about%20your%20membership%20plans';

    protected function setUp(): void
    {
        parent::setUp();
        config(['gym.phone' => '8700362410', 'gym.whatsapp' => '8700362410']);
    }

    public function test_links_are_built_correctly(): void
    {
        $contact = new GymContact();

        $this->assertSame('tel:08700362410', $contact->callUrl());
        $this->assertSame('+91 87003 62410', $contact->displayPhone());
        $this->assertSame(self::WA, $contact->whatsappUrl());
    }

    public function test_number_with_0_or_91_in_config_still_works(): void
    {
        config(['gym.phone' => '+91 87003-62410', 'gym.whatsapp' => '08700362410']);
        $contact = new GymContact();

        $this->assertSame('tel:08700362410', $contact->callUrl());
        $this->assertStringStartsWith('https://wa.me/918700362410?', $contact->whatsappUrl());
    }

    public function test_home_page_has_all_call_and_whatsapp_ctas(): void
    {
        Trainer::create(['name' => 'Coach A', 'role' => 'Coach', 'bio' => 'Bio', 'display_order' => 1]);
        Plan::create(['name' => 'Starter', 'price' => 999, 'period' => '/month', 'features' => 'Floor access', 'is_featured' => false, 'color' => 'bronze', 'button_text' => 'Choose Starter', 'display_order' => 1]);

        $html = $this->get('/')->assertOk();

        $html->assertSee('WhatsApp us')
            ->assertSee('Book your personal training')
            ->assertSee('Call us')
            ->assertSee('Chat with us')
            ->assertSee('Get your membership')
            ->assertSee('href="tel:08700362410"', false)
            ->assertSee(self::WA, false)
            ->assertDontSee('Start your free trial')
            ->assertDontSee('View membership plans')
            ->assertSeeInOrder(['Coaches who', 'Book your personal training', 'id="membership"', 'Get your membership'], false);
    }

    public function test_navbar_and_footer_ctas_on_other_pages(): void
    {
        foreach (['/services', '/membership', '/trainers', '/contact', '/offers'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('nav-whatsapp', false)
                ->assertSee('footer-cta-wa', false)
                ->assertSee('footer-cta-call', false)
                ->assertSee('href="tel:08700362410"', false);
        }
    }
}
