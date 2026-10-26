<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class WebsiteReviewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('review:127.0.0.1');
    }

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function send(array $overrides = [])
    {
        return $this->post(route('gym.reviews.store'), array_merge([
            'review_name' => 'Meera Joshi',
            'review_rating' => 4,
            'review_quote' => 'Friendly coaches and a spotless floor.',
        ], $overrides));
    }

    public function test_home_page_shows_review_form(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Trained with us? Tell us how it went.')
            ->assertSee('action="' . route('gym.reviews.store') . '"', false)
            ->assertSee('name="review_rating" value="5"', false)
            ->assertSee('href="#write-review"', false);
    }

    public function test_visitor_review_waits_for_admin_approval(): void
    {
        $this->send()->assertRedirect(url('/') . '#write-review')->assertSessionHas('review_sent');

        $review = Review::sole();
        $this->assertSame(['Meera Joshi', 4, false, true], [$review->name, $review->rating, $review->is_active, $review->from_website]);

        $this->get('/')->assertSee('Thank you for your review!')->assertDontSee('Friendly coaches and a spotless floor.');

        $admin = $this->admin();
        $this->actingAs($admin, 'admin')->get('/admin/testimonials')
            ->assertOk()
            ->assertSee('New reviews from the website')
            ->assertSee('Friendly coaches and a spotless floor.')
            ->assertSee('class="count-pill"', false);

        $this->actingAs($admin, 'admin')->patch(route('admin.testimonials.approve', $review))->assertRedirect(route('admin.testimonials.index'));

        $this->assertTrue($review->fresh()->is_active);
        $this->get('/')->assertSee('Friendly coaches and a spotless floor.')->assertSee('aria-label="Rated 4 out of 5"', false);
        $this->actingAs($admin, 'admin')->get('/admin/testimonials')->assertDontSee('New reviews from the website')->assertSee('Sent from website');
    }

    public function test_review_is_validated(): void
    {
        $this->send(['review_name' => '', 'review_rating' => 7, 'review_quote' => 'ok'])
            ->assertRedirect(url('/') . '#write-review')
            ->assertSessionHasErrorsIn('review', ['review_name', 'review_rating', 'review_quote']);

        $this->assertSame(0, Review::count());
    }

    public function test_bot_submission_is_ignored(): void
    {
        $this->send(['website' => 'http://spam.example'])->assertSessionHas('review_sent');

        $this->assertSame(0, Review::count());
    }

    public function test_one_visitor_cannot_flood_reviews(): void
    {
        foreach (range(1, 4) as $i) {
            $this->send(['review_quote' => "Review number {$i} from the same visitor."]);
        }

        $this->assertSame(3, Review::count());
    }

    public function test_guest_cannot_approve_reviews(): void
    {
        $review = Review::create(['name' => 'X', 'quote' => 'Pending review.', 'rating' => 5, 'is_active' => false, 'from_website' => true]);

        $this->patch(route('admin.testimonials.approve', $review))->assertRedirect(route('admin.login'));
        $this->assertFalse($review->fresh()->is_active);
    }
}
