<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookingPagesTest extends TestCase
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

    private function plan(): Plan
    {
        return Plan::create([
            'name' => 'Elite', 'price' => 4999, 'period' => '/month', 'features' => "Unlimited classes\nPersonal coach",
            'is_featured' => false, 'color' => 'gold', 'button_text' => 'Choose Elite', 'display_order' => 1,
        ]);
    }

    private function offer(array $overrides = []): Offer
    {
        return Offer::create(array_merge([
            'title' => 'Diwali Lights Offer', 'event' => 'diwali', 'price' => 899, 'original_price' => 4000,
            'valid_until' => '2026-10-30', 'description' => "3 months membership\nFree diet chart",
            'is_active' => true, 'display_order' => 1,
        ], $overrides));
    }

    public function test_plan_button_opens_prefilled_membership_page(): void
    {
        $plan = $this->plan();

        $this->get('/membership')->assertSee(route('gym.book.plan', $plan));

        $this->get(route('gym.book.plan', $plan))
            ->assertOk()
            ->assertSee('Join the Elite plan.')
            ->assertSee('₹4,999')
            ->assertSee('Unlimited classes')
            ->assertSee('Enquiring about')
            ->assertSee('Elite membership (₹4,999/month)')
            ->assertSee('name="ref_type" value="plan"', false)
            ->assertSee('name="ref_id" value="' . $plan->id . '"', false)
            ->assertDontSee('id="book-interest"', false);
    }

    public function test_offer_button_opens_prefilled_offer_page(): void
    {
        $offer = $this->offer();

        $this->get('/offers')->assertSee(route('gym.book.offer', $offer));

        $this->get(route('gym.book.offer', $offer))
            ->assertOk()
            ->assertSee('Happy Diwali')
            ->assertSee('Claim the Diwali Lights Offer.')
            ->assertSee('₹899')
            ->assertSee('₹4,000')
            ->assertSee('78% off')
            ->assertSee('Free diet chart')
            ->assertSee('Valid till 30 Oct 2026 · 10 days left');
    }

    public function test_new_year_offer_page_shows_year(): void
    {
        $offer = $this->offer(['title' => 'New Year Dhamaka', 'event' => 'new_year', 'new_year' => 2027]);

        $this->get(route('gym.book.offer', $offer))->assertOk()->assertSee('Happy New Year 2027');
    }

    public function test_expired_or_hidden_offer_page_is_not_found(): void
    {
        $this->get(route('gym.book.offer', $this->offer(['valid_until' => '2026-10-19'])))->assertNotFound();
        $this->get(route('gym.book.offer', $this->offer(['is_active' => false])))->assertNotFound();
    }

    public function test_trainer_book_slot_opens_personal_coaching_page(): void
    {
        $trainer = Trainer::create(['name' => 'ronnie coleman', 'role' => 'senior trainer', 'bio' => 'Eight time champion.', 'display_order' => 1]);

        $this->get('/trainers')->assertSee(route('gym.book.trainer', $trainer));

        $this->get(route('gym.book.trainer', $trainer))
            ->assertOk()
            ->assertSee('<h1 class="display-lg mt-5 text-white">Personal coaching with Ronnie Coleman.</h1>', false)
            ->assertSee('Book a slot with Ronnie Coleman')
            ->assertSee('Senior trainer')
            ->assertSee('Eight time champion.')
            ->assertSee('Personal coaching with Ronnie Coleman');
    }

    public function test_booking_lead_saves_subject_from_server_not_from_form(): void
    {
        $plan = $this->plan();

        $this->post(route('store.contact'), [
            'name' => 'Asha', 'phone' => '9876543210', 'interest' => 'other',
            'subject' => 'Tampered', 'ref_type' => 'plan', 'ref_id' => $plan->id,
        ])->assertRedirect(route('gym.thankyou'));

        $lead = Contact::sole();
        $this->assertSame('membership', $lead->interest);
        $this->assertSame('Elite membership (₹4,999/month)', $lead->subject);

        $this->get(route('gym.thankyou'))->assertSee('Elite membership (₹4,999/month)');
    }

    public function test_unknown_reference_is_ignored(): void
    {
        $this->post(route('store.contact'), [
            'name' => 'Asha', 'phone' => '9876543210', 'interest' => 'trial', 'ref_type' => 'plan', 'ref_id' => 999,
        ])->assertRedirect(route('gym.thankyou'));

        $lead = Contact::sole();
        $this->assertSame('trial', $lead->interest);
        $this->assertNull($lead->subject);
    }

    public function test_invalid_reference_type_is_rejected(): void
    {
        $this->post(route('store.contact'), [
            'name' => 'Asha', 'phone' => '9876543210', 'ref_type' => 'admin', 'ref_id' => 1,
        ])->assertSessionHasErrors('ref_type');

        $this->assertSame(0, Contact::count());
    }

    public function test_offer_lead_subject_names_the_offer(): void
    {
        $offer = $this->offer();

        $this->post(route('store.contact'), ['name' => 'Ravi', 'phone' => '9876543210', 'ref_type' => 'offer', 'ref_id' => $offer->id]);

        $this->assertSame('Diwali Lights Offer — Happy Diwali (₹899)', Contact::sole()->subject);
        $this->assertSame('offer', Contact::sole()->interest);
    }
}
