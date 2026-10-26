<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\HeroSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSlidesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'tab' => 'Recovery', 'label' => 'New zone', 'title' => 'Recover like a pro athlete.', 'highlight' => 'pro athlete',
            'description' => 'Ice baths and a sauna after every session.', 'pattern' => 'center', 'points_style' => 'capsules',
            'points' => "Ice bath\nSauna\nStretch zone", 'image_position' => 'left',
            'button_text' => 'Book a session', 'button_target' => 'trial', 'button2_text' => 'Ask on WhatsApp', 'button2_target' => 'whatsapp',
            'display_order' => 1, 'is_active' => '1',
        ], $overrides);
    }

    public function test_guest_cannot_manage_homepage(): void
    {
        $this->get('/admin/homepage')->assertRedirect(route('admin.login'));
        $this->post('/admin/homepage/slides', [])->assertRedirect(route('admin.login'));
    }

    public function test_home_uses_default_slides_until_admin_adds_any(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(3, substr_count($html, 'aria-roledescription="slide"'));
        $this->assertStringContainsString('Every champion started as someone who <span class="text-gold-400">refused to quit.</span>', $html);
        $this->assertStringContainsString('Coached, not just supervised.', $html);
        $this->assertStringContainsString('<span class="text-gold-400">20,000 sq ft</span> built for serious training.', $html);
        $this->assertStringContainsString('/ 03', $html);
    }

    public function test_admin_adds_slide_with_compressed_desktop_and_mobile_photos(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.hero.store'), $this->payload(['image' => UploadedFile::fake()->image('wide.jpg', 4000, 2250)]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.hero.index'));

        $slide = HeroSlide::sole();
        $this->assertSame([1920, 1080], array_slice(getimagesizefromstring(Storage::disk('public')->get($slide->image)), 0, 2));
        $this->assertSame([900, 506], array_slice(getimagesizefromstring(Storage::disk('public')->get($slide->image_mobile)), 0, 2));

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'aria-roledescription="slide"'));
        $this->assertStringContainsString('<h1 class="display-xl mt-5 text-white">Recover like a <span class="text-gold-400">pro athlete</span>.</h1>', $html);
        $this->assertStringContainsString('rounded-full border border-white/25 bg-white/5 px-4 py-2 backdrop-blur">Sauna</li>', $html);
        $this->assertStringContainsString('srcset="' . asset('storage/' . $slide->image_mobile) . '"', $html);
        $this->assertStringContainsString('object-position: 25% 50%', $html);
        $this->assertStringContainsString('href="' . route('gym.trial') . '"', $html);
        $this->assertStringNotContainsString('data-hero-toggle', $html);
        $this->assertStringContainsString('rel="preload" as="image" href="' . asset('storage/' . $slide->image) . '"', $html);
    }

    public function test_each_pattern_and_point_style_renders(): void
    {
        HeroSlide::create($this->payload(['tab' => 'One', 'pattern' => 'spotlight', 'points_style' => 'none', 'site_image' => 'hero-strength', 'display_order' => 1]));
        HeroSlide::create($this->payload(['tab' => 'Two', 'title' => 'Card slide', 'highlight' => null, 'pattern' => 'card', 'points_style' => 'checks', 'site_image' => 'hero-coaching', 'display_order' => 2]));
        HeroSlide::create($this->payload(['tab' => 'Three', 'title' => 'Hidden slide', 'highlight' => null, 'is_active' => false, 'site_image' => 'hero-facility', 'display_order' => 3]));

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'aria-roledescription="slide"'));
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('<h2 class="display-lg mt-5 text-white">Card slide</h2>', $html);
        $this->assertStringContainsString('border-l-4 border-gold-500', $html);
        $this->assertStringContainsString('text-gold-400"', $html);
        $this->assertStringContainsString('Call us:', $html);
        $this->assertStringContainsString('/ 02', $html);
        $this->assertStringContainsString('data-hero-toggle', $html);
        $this->assertStringNotContainsString('Hidden slide', $html);
        $this->assertStringNotContainsString('Ice bath</li>', substr($html, 0, strpos($html, 'Card slide')));
    }

    public function test_validation_and_highlight_must_match_heading(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.hero.store'), $this->payload(['tab' => '', 'highlight' => 'not in heading', 'pattern' => 'diagonal', 'button_target' => '']))
            ->assertSessionHasErrors(['tab', 'highlight', 'pattern', 'image', 'button_target']);

        $this->assertSame(0, HeroSlide::count());
    }

    public function test_admin_edits_replaces_photo_and_deletes_slide(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->post(route('admin.hero.store'), $this->payload(['image' => UploadedFile::fake()->image('a.jpg', 2000, 1200)]));
        $slide = HeroSlide::sole();
        [$oldImage, $oldMobile] = [$slide->image, $slide->image_mobile];

        $this->actingAs($admin, 'admin')
            ->put(route('admin.hero.update', $slide), $this->payload(['title' => 'Updated heading', 'highlight' => '', 'image' => UploadedFile::fake()->image('b.jpg', 2000, 1200)]))
            ->assertRedirect(route('admin.hero.index'));

        $slide->refresh();
        $this->assertSame('Updated heading', $slide->title);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertMissing($oldMobile);
        Storage::disk('public')->assertExists($slide->image);

        $this->actingAs($admin, 'admin')->get(route('admin.hero.index'))->assertOk()->assertSee('Updated heading')->assertSee('Centered');

        $this->actingAs($admin, 'admin')->delete(route('admin.hero.destroy', $slide))->assertRedirect(route('admin.hero.index'));
        Storage::disk('public')->assertMissing($slide->image);
        $this->assertSame(0, HeroSlide::count());
        $this->get('/')->assertSee('Every champion started as someone');
    }
}
