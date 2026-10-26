<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Facility;
use App\Models\GalleryPhoto;
use App\Models\VideoTestimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HomeReviewsGalleryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function video(array $overrides = []): VideoTestimonial
    {
        return VideoTestimonial::create(array_merge([
            'title' => 'Lost 12 kg in 4 months', 'name' => 'Rahul', 'youtube_id' => 'dQw4w9WgXcQ', 'is_active' => true, 'display_order' => 1,
        ], $overrides));
    }

    public function test_reviews_are_a_carousel_with_every_review_and_stars(): void
    {
        $this->seed(\Database\Seeders\ReviewSeeder::class);
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-carousel="reviews"', $html);
        $this->assertSame(7, \App\Models\Review::count());
        foreach (\App\Models\Review::all() as $review) {
            $this->assertStringContainsString(e($review->name), $html);
        }
        $this->assertStringContainsString('aria-label="Rated 5 out of 5"', $html);
        $this->assertStringContainsString('aria-label="Rated 4 out of 5"', $html);
    }

    public function test_video_section_hidden_without_videos(): void
    {
        $this->get('/')->assertOk()->assertDontSee('id="video-reviews"', false);
    }

    public function test_video_section_shows_active_videos_as_lazy_embeds(): void
    {
        $this->video();
        $this->video(['title' => 'Hidden video', 'youtube_id' => 'aaaaaaaaaaa', 'is_active' => false]);

        $this->get('/')
            ->assertSee('id="video-reviews"', false)
            ->assertSee('data-carousel="videos"', false)
            ->assertSee('Lost 12 kg in 4 months')
            ->assertSee('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg', false)
            ->assertSee('data-video-src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false)
            ->assertDontSee('<iframe src="https://www.youtube', false)
            ->assertDontSee('Hidden video')
            ->assertDontSee('Watch more on YouTube');

        config(['gym.social.youtube' => 'https://youtube.com/@championclub']);
        $this->get('/')->assertSee('Watch more on YouTube')->assertSee('href="https://youtube.com/@championclub"', false);
    }

    public function test_youtube_links_are_parsed(): void
    {
        $id = 'dQw4w9WgXcQ';

        foreach ([
            "https://www.youtube.com/watch?v={$id}",
            "https://www.youtube.com/watch?feature=share&v={$id}",
            "https://youtu.be/{$id}?si=abc",
            "https://www.youtube.com/shorts/{$id}",
            "https://www.youtube.com/embed/{$id}",
            $id,
        ] as $url) {
            $this->assertSame($id, VideoTestimonial::parseYoutubeId($url), $url);
        }

        $this->assertNull(VideoTestimonial::parseYoutubeId('https://example.com/video'));
    }

    public function test_admin_can_add_edit_and_delete_video_reviews(): void
    {
        $admin = $this->admin();

        $this->get('/admin/videos')->assertRedirect(route('admin.login'));

        $this->actingAs($admin, 'admin')
            ->post(route('admin.videos.store'), ['youtube_url' => 'not a link', 'title' => 'x', 'display_order' => 0])
            ->assertSessionHasErrors('youtube_id');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.videos.store'), [
                'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ', 'title' => 'First month review', 'name' => 'Priya',
                'display_order' => 1, 'is_active' => '1',
            ])
            ->assertRedirect(route('admin.testimonials.index') . '#videos');

        $video = VideoTestimonial::sole();
        $this->assertSame('dQw4w9WgXcQ', $video->youtube_id);

        $this->actingAs($admin, 'admin')->get(route('admin.testimonials.index'))->assertOk()->assertSee('First month review');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.videos.update', $video), [
                'youtube_url' => 'https://www.youtube.com/watch?v=aaaaaaaaaaa', 'title' => 'Updated', 'display_order' => 2,
            ])
            ->assertRedirect(route('admin.testimonials.index') . '#videos');
        $this->assertSame(['aaaaaaaaaaa', 'Updated', false], [$video->fresh()->youtube_id, $video->fresh()->title, $video->fresh()->is_active]);

        $this->actingAs($admin, 'admin')->delete(route('admin.videos.destroy', $video))->assertRedirect(route('admin.testimonials.index') . '#videos');
        $this->assertSame(0, VideoTestimonial::count());
    }

    public function test_navbar_order_about_first_contact_last_without_facilities(): void
    {
        $html = $this->get('/')->getContent();
        preg_match('~<nav aria-label="Primary".*?</nav>~s', $html, $nav);

        $this->assertNotEmpty($nav);
        preg_match_all('~class="nav-link[^"]*"[^>]*>\s*([A-Za-z]+)~', $nav[0], $labels);
        $this->assertSame(['About', 'Services', 'Trainers', 'Membership', 'Offers', 'Gallery', 'Contact'], $labels[1]);
        $this->assertStringNotContainsString(route('gym.facilities'), $nav[0]);

        preg_match('~<footer.*?</footer>~s', $html, $footer);
        preg_match('~Explore</h2>\s*<ul.*?</ul>~s', $footer[0], $explore);
        preg_match_all('~>([A-Za-z]+)</a>~', $explore[0], $links);
        $this->assertSame(['About', 'Services', 'Trainers', 'Membership', 'Offers', 'Gallery'], $links[1]);
        $this->assertStringContainsString('Get directions', $footer[0]);
    }

    public function test_statement_section_removed_and_facilities_has_explore_button(): void
    {
        Facility::create(['title' => 'Cardio zone', 'text' => 'Treadmills.', 'is_active' => true, 'display_order' => 1]);

        $this->get('/')
            ->assertDontSee("Strength is a skill before it's a number.")
            ->assertDontSee('Train hard.')
            ->assertSeeInOrder(['Cardio zone', 'href="' . route('gym.facilities') . '"', 'Explore the facilities'], false);
    }

    public function test_home_gallery_shows_five_photos_and_links_to_gallery(): void
    {
        foreach (range(1, 7) as $i) {
            GalleryPhoto::create(['image' => "gallery/{$i}.webp", 'width' => 1400, 'height' => 933, 'alt' => "Photo {$i}", 'is_active' => true, 'display_order' => $i]);
        }

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(5, substr_count($html, 'home-gallery-item'));
        $this->assertStringContainsString('alt="Photo 5"', $html);
        $this->assertStringNotContainsString('alt="Photo 6"', $html);
        $this->assertStringContainsString('View full gallery', $html);
    }

    public function test_home_gallery_hidden_without_photos(): void
    {
        $this->get('/')->assertDontSee('home-gallery', false);
    }
}
