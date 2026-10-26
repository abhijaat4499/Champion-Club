<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Plan;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_slider_has_three_slides_and_one_h1(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(3, substr_count($html, 'aria-roledescription="slide"'));
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('data-hero-toggle', $html);
        $this->assertStringContainsString(route('gym.trial'), $html);
    }

    public function test_long_trainer_bio_gets_read_more_link(): void
    {
        Trainer::create(['name' => 'Short Bio', 'role' => 'Coach', 'bio' => 'Strong and kind.', 'display_order' => 1]);
        Trainer::create(['name' => 'Long Bio', 'role' => 'Coach', 'bio' => str_repeat('Coaching athletes back from injury to competition. ', 4), 'display_order' => 2]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Strong and kind.', $html);
        $this->assertSame(1, substr_count($html, 'class="read-more'));
        $this->assertMatchesRegularExpression('#href="' . preg_quote(route('gym.trainers'), '#') . '" class="read-more[^"]*">read more</a>#', $html);
    }

    public function test_home_plans_keep_choose_button(): void
    {
        $plan = Plan::create([
            'name' => 'Starter', 'price' => 999, 'period' => '/month', 'features' => "A\nB\nC",
            'is_featured' => false, 'color' => 'bronze', 'button_text' => 'Choose Starter', 'display_order' => 1,
        ]);

        $this->get('/')
            ->assertSee('Choose Starter')
            ->assertSee('₹999')
            ->assertSee(route('gym.book.plan', $plan));
    }

    public function test_trainer_cards_have_book_slot_button(): void
    {
        $trainer = Trainer::create(['name' => 'Coach A', 'role' => 'Coach', 'bio' => 'Bio', 'display_order' => 1]);

        $this->get('/')
            ->assertSee('Book slot')
            ->assertSee(route('gym.book.trainer', $trainer));
    }

    public function test_member_stories_come_from_reviews(): void
    {
        \App\Models\Review::create(['name' => 'Ritika Sinha', 'quote' => 'Great coaches.', 'rating' => 5, 'is_active' => true, 'display_order' => 1]);
        \App\Models\Review::create(['name' => 'Hidden Person', 'quote' => 'Hidden.', 'rating' => 5, 'is_active' => false, 'display_order' => 2]);

        $this->get('/')->assertOk()
            ->assertSee('What our members say')
            ->assertSee('Ritika Sinha')
            ->assertDontSee('Member since')
            ->assertDontSee('Hidden Person');
    }

    public function test_review_form_shows_even_when_no_reviews(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('id="write-review"', false)
            ->assertDontSee('data-carousel="reviews"', false);
    }

    public function test_seo_tags_and_schema(): void
    {
        $this->get('/')
            ->assertSee('<link rel="canonical" href="' . url('/') . '">', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('"@type":"ExerciseGym"', false);

        $this->get('/gym')->assertSee('<link rel="canonical" href="' . url('/') . '">', false);
    }

    public function test_new_pages_render(): void
    {
        foreach (['/about' => 'A gym for people who actually train.', '/facilities' => '20,000 sq ft built for serious training.', '/free-trial' => 'Your first session is on us.'] as $url => $heading) {
            $this->get($url)->assertOk()->assertSee($heading);
        }

        $this->get('/free-trial')->assertSee('name="phone"', false)->assertSee('action="' . route('store.contact') . '"', false);
    }

    public function test_membership_page_has_faq_schema(): void
    {
        $this->get('/membership')->assertOk()->assertSee('"@type":"FAQPage"', false)->assertSee('Is there a joining fee?');
    }

    public function test_quick_form_accepts_phone_only(): void
    {
        $this->post('/contact', ['name' => 'Aman', 'phone' => '9812345678', 'interest' => 'trial'])
            ->assertRedirect(route('gym.thankyou'));

        $lead = Contact::firstOrFail();
        $this->assertNull($lead->email);
        $this->assertNull($lead->message);
    }

    public function test_form_needs_phone_or_email(): void
    {
        $this->from('/free-trial')->post('/contact', ['name' => 'Aman'])
            ->assertSessionHasErrors(['phone', 'email']);

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_sitemap_lists_public_pages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<loc>' . route('gym.trial') . '</loc>', false)
            ->assertSee('<loc>' . route('gym.facilities') . '</loc>', false);
    }
}
