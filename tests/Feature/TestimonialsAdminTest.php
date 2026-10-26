<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Review;
use App\Models\Setting;
use App\Models\VideoTestimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TestimonialsAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    public function test_guest_cannot_open_testimonials(): void
    {
        $this->get('/admin/testimonials')->assertRedirect(route('admin.login'));
        $this->put('/admin/testimonials/youtube', [])->assertRedirect(route('admin.login'));
    }

    public function test_sidebar_shows_testimonials_and_page_lists_reviews_and_videos(): void
    {
        Review::create(['name' => 'Sneha Kapoor', 'quote' => 'Clean and friendly.', 'rating' => 5, 'is_active' => true, 'display_order' => 1]);
        VideoTestimonial::create(['title' => 'My first month', 'youtube_id' => 'dQw4w9WgXcQ', 'is_active' => true, 'display_order' => 1]);

        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/testimonials')
            ->assertOk()
            ->assertSee('>Testimonials', false)
            ->assertDontSee('Video Reviews</a>', false)
            ->assertSeeInOrder(['Client reviews', 'Sneha Kapoor', 'Video reviews', 'My first month', 'YouTube channel link'])
            ->assertSee('+ Add Review')
            ->assertSee('+ Add Video');

        $this->actingAs(Admin::first(), 'admin')->get('/admin/videos')->assertRedirect(route('admin.testimonials.index') . '#videos');
    }

    public function test_admin_adds_edits_and_deletes_review(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.testimonials.store'), ['name' => '', 'quote' => '', 'rating' => 9, 'display_order' => 0])
            ->assertSessionHasErrors(['name', 'quote', 'rating']);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.testimonials.store'), [
                'name' => 'Amit Rana', 'quote' => 'Best coaching in the city.', 'rating' => 4, 'display_order' => 1, 'is_active' => '1',
            ])
            ->assertRedirect(route('admin.testimonials.index'));

        $review = Review::sole();
        $this->get('/')->assertSee('Amit Rana')->assertSee('Best coaching in the city.')->assertSee('aria-label="Rated 4 out of 5"', false);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.testimonials.update', $review), ['name' => 'Amit R.', 'quote' => 'Updated.', 'rating' => 5, 'display_order' => 2])
            ->assertRedirect(route('admin.testimonials.index'));

        $this->assertSame(['Amit R.', false], [$review->fresh()->name, $review->fresh()->is_active]);
        $this->get('/')->assertDontSee('Amit R.');

        $this->actingAs($admin, 'admin')->delete(route('admin.testimonials.destroy', $review))->assertRedirect(route('admin.testimonials.index'));
        $this->assertSame(0, Review::count());
    }

    public function test_admin_updates_youtube_channel_link(): void
    {
        $admin = $this->admin();
        VideoTestimonial::create(['title' => 'Video', 'youtube_id' => 'dQw4w9WgXcQ', 'is_active' => true, 'display_order' => 1]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.testimonials.youtube'), ['youtube' => 'not a link'])
            ->assertSessionHasErrors('youtube');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.testimonials.youtube'), ['youtube' => 'https://www.youtube.com/@championclub'])
            ->assertRedirect(route('admin.testimonials.index') . '#videos');

        $this->assertSame('https://www.youtube.com/@championclub', Setting::where('key', 'social')->value('value')['youtube']);
        $this->get('/')->assertSee('Watch more on YouTube')->assertSee('href="https://www.youtube.com/@championclub"', false);

        $this->actingAs($admin, 'admin')->put(route('admin.testimonials.youtube'), ['youtube' => '']);
        $this->get('/')->assertDontSee('Watch more on YouTube');
    }

    public function test_saving_about_and_contact_keeps_youtube_link(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin, 'admin')->put(route('admin.testimonials.youtube'), ['youtube' => 'https://www.youtube.com/@championclub']);

        $this->actingAs($admin, 'admin')->put('/admin/about', [
            'phone' => '8700362410', 'whatsapp' => '8700362410', 'email' => 'hello@championclub.in', 'whatsapp_message' => 'Hi',
            'address' => ['street' => '12 Strength Avenue', 'locality' => 'Gurugram', 'region' => 'Haryana'],
            'hours' => [['from' => 'Mo', 'to' => 'Sa', 'opens' => '06:00', 'closes' => '22:00']],
            'social' => ['instagram' => 'https://instagram.com/cc', 'facebook' => ''],
            'years_running' => 8, 'about_title' => 'Heading', 'about_story' => 'Story.',
            'highlights' => config('gym.highlights'),
        ])->assertSessionHasNoErrors();

        $social = Setting::where('key', 'social')->value('value');
        $this->assertSame('https://www.youtube.com/@championclub', $social['youtube']);
        $this->assertSame('https://instagram.com/cc', $social['instagram']);
    }
}
