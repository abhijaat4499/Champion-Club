<?php

namespace Tests\Feature;

use App\Models\AboutSection;
use App\Models\Admin;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function settingsPayload(array $extra = []): array
    {
        return array_merge([
            'phone' => '8700362410', 'whatsapp' => '8700362410', 'email' => 'hello@championclub.in',
            'whatsapp_message' => 'Hi', 'address' => ['street' => '12 Strength Avenue', 'locality' => 'Gurugram', 'region' => 'Haryana'],
            'hours' => [['from' => 'Mo', 'to' => 'Sa', 'opens' => '06:00', 'closes' => '22:00']],
            'social' => ['instagram' => '', 'facebook' => '', 'youtube' => ''],
            'years_running' => 8, 'about_title' => 'Built by coaches.', 'about_story' => 'Our story.',
            'highlights' => array_map(fn ($h) => $h, config('gym.highlights')),
        ], $extra);
    }

    public function test_guest_cannot_manage_about_sections(): void
    {
        $this->get('/admin/about-sections')->assertRedirect(route('admin.login'));
        $this->post('/admin/about-sections', [])->assertRedirect(route('admin.login'));
    }

    public function test_about_page_shows_live_services_instead_of_static_values(): void
    {
        Service::create(['title' => 'Group HIIT', 'slug' => 'group-hiit', 'icon' => 'stopwatch', 'summary' => 'Intervals.', 'description' => 'x', 'is_active' => true, 'display_order' => 1]);
        Service::create(['title' => 'Secret Class', 'slug' => 'secret', 'icon' => 'yoga', 'summary' => 'x', 'description' => 'x', 'is_active' => false, 'display_order' => 2]);

        $this->get('/about')
            ->assertOk()
            ->assertDontSee('Three things most gyms only claim to offer.')
            ->assertSee('What we offer')
            ->assertSee('Group HIIT')
            ->assertSee(route('gym.service', 'group-hiit'))
            ->assertDontSee('Secret Class');
    }

    public function test_admin_adds_section_with_photo_and_it_shows_on_about_page(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.about-sections.store'), [
                'eyebrow' => "What's new", 'title' => 'Our recovery zone is open',
                'body' => "Why we built it\nRecovery matters as much as training.\n\nBook a slot at the front desk.",
                'display_order' => 1, 'image_position' => 'upper', 'is_active' => '1',
                'image' => UploadedFile::fake()->image('zone.jpg', 2000, 1500),
            ])
            ->assertRedirect(route('admin.site.edit') . '#about-gym');

        $section = AboutSection::sole();
        Storage::disk('public')->assertExists($section->image);

        $this->get('/about')
            ->assertSee("What's new")
            ->assertSee('Our recovery zone is open')
            ->assertSee('<h3>Why we built it</h3>', false)
            ->assertSee('<p>Recovery matters as much as training.</p>', false)
            ->assertSee('object-position: 50% 25%', false)
            ->assertSee('lg:sticky', false);
    }

    public function test_section_without_photo_and_hidden_section(): void
    {
        AboutSection::create(['title' => 'New timings from June', 'body' => 'We now open at 5 AM.', 'is_active' => true, 'display_order' => 1]);
        AboutSection::create(['title' => 'Draft news', 'body' => 'Not ready.', 'is_active' => false, 'display_order' => 2]);

        $html = $this->get('/about')->assertOk()->getContent();

        $this->assertStringContainsString('New timings from June', $html);
        $this->assertStringContainsString('We now open at 5 AM.', $html);
        $this->assertStringNotContainsString('Draft news', $html);
    }

    public function test_admin_edits_and_deletes_section(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('about/old.webp', 'x');
        $section = AboutSection::create(['title' => 'Old', 'body' => 'Old text.', 'image' => 'about/old.webp', 'is_active' => true, 'display_order' => 1]);
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.about-sections.update', $section), ['title' => 'Updated heading', 'body' => 'New text.', 'display_order' => 2, 'remove_image' => '1'])
            ->assertRedirect(route('admin.site.edit') . '#about-gym');

        $section->refresh();
        $this->assertSame('Updated heading', $section->title);
        $this->assertNull($section->image);
        $this->assertFalse($section->is_active);
        Storage::disk('public')->assertMissing('about/old.webp');

        $this->actingAs($admin, 'admin')->get('/admin/about')->assertOk()->assertSee('Updated heading');
        $this->actingAs($admin, 'admin')->delete(route('admin.about-sections.destroy', $section))->assertRedirect(route('admin.site.edit') . '#about-gym');
        $this->assertSame(0, AboutSection::count());
    }

    public function test_section_requires_heading_and_text(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.about-sections.store'), ['display_order' => 0])
            ->assertSessionHasErrors(['title', 'body']);
    }

    public function test_approach_heading_and_single_photo_upload_default_and_hide(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->get('/about')
            ->assertSee('Coached, not just supervised.')
            ->assertSee(config('site-images.about-detail.src'))
            ->assertDontSee(config('site-images.fac-plates.src'))
            ->assertSee('lg:sticky lg:top-32 lg:col-span-5', false);

        $this->actingAs($admin, 'admin')
            ->put('/admin/about', $this->settingsPayload(['about_image_main' => UploadedFile::fake()->image('main.jpg', 1600, 2000)]))
            ->assertSessionHasNoErrors();

        $images = Setting::where('key', 'about_images')->value('value');
        $this->assertStringStartsWith('about/', $images['main']);
        Storage::disk('public')->assertExists($images['main']);

        $this->get('/about')->assertSee('Built by coaches.')->assertSee('storage/' . $images['main']);

        $this->actingAs($admin, 'admin')
            ->put('/admin/about', $this->settingsPayload(['about_image_main_mode' => 'hide']))
            ->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($images['main']);
        $this->get('/about')->assertDontSee(config('site-images.about-detail.src'))->assertSee('years coaching in');

        $this->actingAs($admin, 'admin')
            ->put('/admin/about', $this->settingsPayload(['about_image_main_mode' => 'default']))
            ->assertSessionHasNoErrors();

        $this->get('/about')->assertSee(config('site-images.about-detail.src'));
    }

    public function test_about_settings_card_lists_sections_with_add_button(): void
    {
        AboutSection::create(['title' => 'Listed section', 'body' => 'Text.', 'is_active' => true, 'display_order' => 1]);
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->get('/admin/about')
            ->assertOk()
            ->assertSee('id="about-gym"', false)
            ->assertSee('+ Add section')
            ->assertSee(route('admin.about-sections.create'))
            ->assertSee('Listed section')
            ->assertSee('name="about_title"', false)
            ->assertDontSee('name="about_image_secondary"', false)
            ->assertDontSee('name="social[youtube]"', false);

        $this->actingAs($admin, 'admin')->get('/admin/about-sections')->assertRedirect(route('admin.site.edit') . '#about-gym');
    }
}
