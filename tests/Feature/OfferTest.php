<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-10-20 10:00:00', 'Asia/Kolkata'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function offer(array $overrides = []): Offer
    {
        return Offer::create(array_merge([
            'title' => 'Festive Deal',
            'event' => 'other',
            'price' => 999,
            'original_price' => 1999,
            'valid_until' => '2026-10-30',
            'description' => "Line one\nLine two",
            'is_active' => true,
            'display_order' => 0,
        ], $overrides));
    }

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    public function test_offers_page_shows_sorry_message_when_empty(): void
    {
        $this->get('/offers')
            ->assertOk()
            ->assertSee('Sorry, there are currently no offers.');
    }

    public function test_each_event_gets_its_own_card_design(): void
    {
        foreach (array_keys(Offer::EVENTS) as $i => $event) {
            $this->offer(['title' => "Offer $event", 'event' => $event, 'display_order' => $i]);
        }

        $response = $this->get('/offers')->assertOk()->assertDontSee('Sorry, there are currently no offers.');

        foreach (['new_year' => 'Happy New Year', 'holi' => 'Happy Holi', 'diwali' => 'Happy Diwali', 'christmas' => 'Merry Christmas', 'other' => 'Special offer'] as $event => $greeting) {
            $response->assertSee("offer-card offer-$event", false)->assertSee($greeting)->assertSee("Offer $event");
        }
    }

    public function test_card_shows_price_discount_deadline_and_points(): void
    {
        $this->offer(['event' => 'diwali', 'title' => 'Diwali Special', 'price' => 1500, 'original_price' => 3000, 'valid_until' => '2026-10-25']);

        $this->get('/offers')
            ->assertSee('Diwali Special')
            ->assertSee('₹1,500')
            ->assertSee('₹3,000')
            ->assertSee('50% OFF')
            ->assertSee('Valid till 25 Oct 2026')
            ->assertSee('5 days left')
            ->assertSee('Line one')
            ->assertSee('Line two');
    }

    public function test_last_day_offer_still_shows_but_expired_and_hidden_do_not(): void
    {
        $this->offer(['title' => 'Ends Today', 'valid_until' => '2026-10-20']);
        $this->offer(['title' => 'Already Over', 'valid_until' => '2026-10-19']);
        $this->offer(['title' => 'Turned Off', 'is_active' => false]);

        $this->get('/offers')
            ->assertSee('Ends Today')
            ->assertSee('Last day today')
            ->assertDontSee('Already Over')
            ->assertDontSee('Turned Off');
    }

    public function test_new_year_offer_in_december_shows_next_year(): void
    {
        $this->offer(['event' => 'new_year', 'valid_until' => '2026-12-31']);

        $this->get('/offers')->assertSee('Happy New Year 2027');
    }

    public function test_card_uses_year_chosen_by_admin(): void
    {
        $this->offer(['event' => 'new_year', 'new_year' => 2029, 'valid_until' => '2026-12-31']);

        $this->get('/offers')->assertSee('Happy New Year 2029')->assertDontSee('Happy New Year 2027');
    }

    public function test_new_year_is_a_typed_year_prefilled_with_next_year(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/offers/create')
            ->assertSee('Which New Year?')
            ->assertSee('type="number" id="new_year" name="new_year"', false)
            ->assertSee('min="2026" max="2046"', false)
            ->assertSee('value="2027"', false)
            ->assertSee('Card will show:')
            ->assertSee('Happy New Year 2027')
            ->assertSee('name="starts_on" value="2026-10-20"', false);
    }

    private function newYearPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'NY Deal',
            'event' => 'new_year',
            'price' => 999,
            'starts_on' => '2026-10-20',
            'valid_until' => '2026-12-31',
            'description' => 'Deal',
            'display_order' => 0,
            'is_active' => '1',
        ], $overrides);
    }

    public function test_new_year_needs_a_valid_upcoming_year(): void
    {
        $admin = $this->admin();

        foreach ([[], ['new_year' => 2025], ['new_year' => 2047], ['new_year' => 'abc']] as $bad) {
            $this->actingAs($admin, 'admin')
                ->from('/admin/offers/create')
                ->post('/admin/offers', $this->newYearPayload($bad))
                ->assertSessionHasErrors('new_year');
        }

        $this->assertDatabaseCount('offers', 0);

        $this->actingAs($admin, 'admin')
            ->post('/admin/offers', $this->newYearPayload(['new_year' => 2031]))
            ->assertSessionHasNoErrors();

        $this->assertSame(2031, Offer::firstOrFail()->new_year);
        $this->get('/offers')->assertSee('Happy New Year 2031');
    }

    public function test_other_events_do_not_keep_a_year(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->post('/admin/offers', $this->newYearPayload(['event' => 'diwali', 'new_year' => 2027]))
            ->assertSessionHasNoErrors();

        $this->assertNull(Offer::firstOrFail()->new_year);
    }

    public function test_editing_old_offer_keeps_its_past_year_option(): void
    {
        $offer = $this->offer(['event' => 'new_year', 'new_year' => 2027, 'valid_until' => '2026-12-31']);
        Carbon::setTestNow(Carbon::parse('2028-03-01 10:00:00', 'Asia/Kolkata'));

        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->get("/admin/offers/{$offer->id}/edit")
            ->assertSee('value="2027"', false)
            ->assertSee('min="2027"', false);

        $this->actingAs($admin, 'admin')
            ->put("/admin/offers/{$offer->id}", $this->newYearPayload(['new_year' => 2027]))
            ->assertSessionHasNoErrors();
    }

    public function test_home_page_hides_offer_section_when_no_offers(): void
    {
        $this->offer(['title' => 'Old One', 'valid_until' => '2026-10-19']);
        $this->offer(['title' => 'Hidden One', 'is_active' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('id="offers"', false)
            ->assertDontSee('home-offers', false);
    }

    public function test_home_page_shows_only_the_top_offer(): void
    {
        \App\Models\Review::create(['name' => 'Asha', 'quote' => 'Great.', 'rating' => 5, 'is_active' => true, 'display_order' => 1]);
        $this->offer(['title' => 'Second Deal', 'event' => 'holi', 'display_order' => 2]);
        $this->offer(['title' => 'Top Deal', 'event' => 'diwali', 'display_order' => 1]);
        $this->offer(['title' => 'Third Deal', 'event' => 'christmas', 'display_order' => 3]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Limited time offers')
            ->assertSee('Top Deal')
            ->assertSee('offer-card offer-diwali', false)
            ->assertDontSee('Second Deal')
            ->assertDontSee('Third Deal')
            ->assertSee('Go to offers')
            ->assertSeeInOrder(['Go to offers', 'Get the offer'])
            ->assertSee('+2 more')
            ->assertSee(route('gym.offers'))
            ->assertSeeInOrder(['id="offers"', 'Limited time offers', 'What our members say'], false);

        $this->get('/offers')->assertSeeInOrder(['Top Deal', 'Second Deal', 'Third Deal']);
    }

    public function test_home_page_single_offer_has_no_more_count(): void
    {
        $this->offer(['title' => 'Only Deal']);

        $this->get('/')->assertSee('Only Deal')->assertSee('Go to offers')->assertDontSee('more</span>', false);
    }

    public function test_guest_cannot_open_admin_offers(): void
    {
        $this->get('/admin/offers')->assertRedirect(route('admin.login'));
        $this->post('/admin/offers', [])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_edit_and_delete_offer(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->get('/admin/offers/create')
            ->assertOk()->assertSee('Choose event')->assertSee('Christmas');

        $this->actingAs($admin, 'admin')->post('/admin/offers', [
            'title' => 'Xmas Special',
            'event' => 'christmas',
            'price' => 2000,
            'original_price' => 2500,
            'starts_on' => '2026-10-20',
            'valid_until' => '2026-12-26',
            'description' => 'Free t-shirt',
            'display_order' => 1,
            'is_active' => '1',
        ])->assertRedirect(route('admin.offers.index'));

        $offer = Offer::firstOrFail();
        $this->assertSame('christmas', $offer->event);
        $this->assertTrue($offer->is_active);

        $this->actingAs($admin, 'admin')->get('/admin/offers')
            ->assertSee('Xmas Special')->assertSee('Live');

        $this->actingAs($admin, 'admin')->put("/admin/offers/{$offer->id}", [
            'title' => 'Xmas Special',
            'event' => 'new_year',
            'new_year' => 2028,
            'price' => 1800,
            'starts_on' => '2026-10-20',
            'valid_until' => '2026-12-31',
            'description' => 'Free t-shirt',
            'display_order' => 1,
        ])->assertRedirect(route('admin.offers.index'));

        $offer->refresh();
        $this->assertSame('new_year', $offer->event);
        $this->assertSame(2028, $offer->new_year);
        $this->assertFalse($offer->is_active);
        $this->get('/offers')->assertSee('Sorry, there are currently no offers.');

        $this->actingAs($admin, 'admin')->delete("/admin/offers/{$offer->id}")
            ->assertRedirect(route('admin.offers.index'));
        $this->assertDatabaseCount('offers', 0);
    }

    public function test_scheduled_offer_stays_hidden_until_its_start_date(): void
    {
        $offer = $this->offer(['title' => 'Future Deal', 'starts_on' => '2026-10-25', 'valid_until' => '2026-10-31']);

        $this->get('/offers')->assertDontSee('Future Deal');
        $this->get('/')->assertDontSee('Future Deal');
        $this->get(route('gym.book.offer', $offer))->assertNotFound();
        $this->actingAs($this->admin(), 'admin')->get('/admin/offers')
            ->assertSee('Scheduled')->assertSee('25 Oct – 31 Oct 2026');

        Carbon::setTestNow(Carbon::parse('2026-10-25 09:00:00', 'Asia/Kolkata'));

        $this->get('/offers')->assertSee('Future Deal');
        $this->get(route('gym.book.offer', $offer))->assertOk();
    }

    public function test_last_date_cannot_be_before_start_date(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->post('/admin/offers', $this->newYearPayload(['event' => 'diwali', 'starts_on' => '2026-11-10', 'valid_until' => '2026-11-01']))
            ->assertSessionHasErrors('valid_until');

        $this->assertDatabaseCount('offers', 0);
    }

    public function test_admin_validation(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->from('/admin/offers/create')
            ->post('/admin/offers', [
                'title' => '',
                'event' => 'eid-unknown',
                'price' => 1000,
                'original_price' => 500,
                'valid_until' => '2026-10-19',
                'description' => '',
                'display_order' => 0,
            ])
            ->assertSessionHasErrors(['title', 'event', 'original_price', 'starts_on', 'valid_until', 'description']);

        $this->assertDatabaseCount('offers', 0);
    }

    public function test_offers_link_in_site_and_admin_navigation(): void
    {
        $this->get('/offers')->assertSee(route('gym.offers'));
        $this->actingAs($this->admin(), 'admin')->get('/admin/dashboard')->assertSee(route('admin.offers.index'));
    }
}
