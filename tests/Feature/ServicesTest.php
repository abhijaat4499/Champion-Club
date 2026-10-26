<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Contact;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function service(array $overrides = []): Service
    {
        return Service::create(array_merge([
            'title' => 'Group HIIT', 'slug' => 'group-hiit', 'icon' => 'stopwatch',
            'summary' => 'High-intensity interval sessions.', 'description' => "First paragraph.\n\nSecond paragraph.",
            'highlights' => "45-minute sessions\nNew format every week", 'is_active' => true, 'display_order' => 1,
        ], $overrides));
    }

    public function test_navbar_shows_services_instead_of_programs(): void
    {
        $html = $this->get('/')->assertOk();

        $html->assertSee(route('gym.services'))
            ->assertSee('>Services</a>', false)
            ->assertDontSee('>Programs</a>', false)
            ->assertDontSee('data-carousel="programs"', false);
    }

    public function test_old_program_url_redirects_to_services(): void
    {
        $this->get('/program')->assertStatus(301)->assertRedirect('/services');
    }

    public function test_services_page_lists_only_active_services(): void
    {
        $this->service();
        $this->service(['title' => 'Secret Class', 'slug' => 'secret-class', 'is_active' => false]);

        $this->get('/services')
            ->assertOk()
            ->assertSee('Group HIIT')
            ->assertSee(route('gym.service', 'group-hiit'))
            ->assertDontSee('Secret Class');
    }

    public function test_services_page_shows_message_when_empty(): void
    {
        $this->get('/services')->assertOk()->assertSee('Services will be added soon.');
    }

    public function test_service_page_shows_details_and_prefilled_form(): void
    {
        $service = $this->service();

        $this->get('/services/group-hiit')
            ->assertOk()
            ->assertSee('<h1', false)
            ->assertSee('Group HIIT')
            ->assertSee('45-minute sessions')
            ->assertSee('Second paragraph.')
            ->assertSee('Book Group HIIT')
            ->assertSee(route('gym.book.service', 'group-hiit'))
            ->assertDontSee('name="ref_type"', false);

        $this->get('/services/group-hiit/book')
            ->assertOk()
            ->assertSee('Book Group HIIT.')
            ->assertSee('High-intensity interval sessions.')
            ->assertSee('Service: Group HIIT')
            ->assertSee('name="ref_type" value="service"', false)
            ->assertSee('name="ref_id" value="' . $service->id . '"', false);

        $this->post(route('store.contact'), ['name' => 'Neha', 'phone' => '9876543210', 'ref_type' => 'service', 'ref_id' => $service->id]);

        $this->assertSame('Service: Group HIIT', Contact::sole()->subject);
        $this->assertSame('service', Contact::sole()->interest);
    }

    public function test_hidden_service_page_is_not_found(): void
    {
        $this->service(['is_active' => false]);

        $this->get('/services/group-hiit')->assertNotFound();
        $this->get('/services/group-hiit/book')->assertNotFound();
        $this->get('/services/missing')->assertNotFound();
    }

    public function test_description_short_lines_become_headings(): void
    {
        $this->service(['description' => "What is HIIT?\nShort bursts of hard work with rest in between.\n\nWhy it works\nIt builds fitness in less time."]);

        $this->get('/services/group-hiit')
            ->assertSee('<h3>What is HIIT?</h3>', false)
            ->assertSee('<h3>Why it works</h3>', false)
            ->assertSee('<p>Short bursts of hard work with rest in between.</p>', false);
    }

    public function test_image_focus_is_applied_and_saved_by_admin(): void
    {
        $service = $this->service(['image' => 'services/tall.webp', 'image_position' => 'upper']);

        $this->get('/services/group-hiit')
            ->assertSee('object-position: 50% 25%', false)
            ->assertSee('object-position: 50% 0%', false);

        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.services.update', $service), [
                'title' => 'Group HIIT', 'slug' => 'group-hiit', 'icon' => 'stopwatch', 'summary' => 'x',
                'description' => 'y', 'display_order' => 1, 'is_active' => '1', 'image_position' => 'lower',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('lower', $service->fresh()->image_position);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.services.update', $service), [
                'title' => 'Group HIIT', 'slug' => 'group-hiit', 'icon' => 'stopwatch', 'summary' => 'x',
                'description' => 'y', 'display_order' => 1, 'image_position' => 'sideways',
            ])
            ->assertSessionHasErrors('image_position');
    }

    public function test_sentence_broken_across_lines_stays_one_paragraph(): void
    {
        $this->service(['description' => "Cardio raises the heart rate. Short for\n“cardiovascular,” it boosts heart health.\nSecond paragraph here."]);

        $this->get('/services/group-hiit')
            ->assertSee('<p>Cardio raises the heart rate. Short for “cardiovascular,” it boosts heart health.</p>', false)
            ->assertSee('<p>Second paragraph here.</p>', false);
    }

    public function test_free_trial_and_sitemap_use_services(): void
    {
        $this->service();

        $this->get('/free-trial')->assertOk()->assertSee('Services you can try')->assertSee(route('gym.service', 'group-hiit'));
        $this->get('/sitemap.xml')->assertOk()->assertSee(route('gym.services'))->assertSee(route('gym.service', 'group-hiit'));
    }

    public function test_guest_cannot_manage_services(): void
    {
        $this->get('/admin/services')->assertRedirect(route('admin.login'));
        $this->post('/admin/services', [])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_service_with_image_and_auto_slug(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.services.store'), [
                'title' => 'Kids Boxing Class', 'slug' => '', 'icon' => 'boxing',
                'summary' => 'Boxing basics for kids.', 'description' => 'Fun and safe sessions.',
                'highlights' => "Ages 8–14\nWeekend batches", 'display_order' => 3, 'is_active' => '1',
                'image' => UploadedFile::fake()->image('kids.jpg', 800, 600),
            ])
            ->assertRedirect(route('admin.services.index'));

        $service = Service::sole();
        $this->assertSame('kids-boxing-class', $service->slug);
        $this->assertTrue($service->is_active);
        Storage::disk('public')->assertExists($service->image);

        $this->get('/services/kids-boxing-class')->assertOk()->assertSee('Kids Boxing Class')->assertSee('Weekend batches');
        $this->get(route('admin.services.index'))->assertOk()->assertSee('Kids Boxing Class')->assertSee('/services/kids-boxing-class');
    }

    public function test_admin_cannot_reuse_slug(): void
    {
        $this->service();

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.services.store'), [
                'title' => 'Group HIIT', 'icon' => 'stopwatch', 'summary' => 'x', 'description' => 'y', 'display_order' => 0,
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_hide_and_delete_service(): void
    {
        Storage::fake('public');
        $service = $this->service(['image' => 'services/old.jpg']);
        Storage::disk('public')->put('services/old.jpg', 'x');
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.services.update', $service), [
                'title' => 'Group HIIT Plus', 'slug' => 'group-hiit', 'icon' => 'fire',
                'summary' => 'Updated.', 'description' => 'Updated text.', 'display_order' => 2, 'remove_image' => '1',
            ])
            ->assertRedirect(route('admin.services.index'));

        $service->refresh();
        $this->assertSame('Group HIIT Plus', $service->title);
        $this->assertFalse($service->is_active);
        $this->assertNull($service->image);
        Storage::disk('public')->assertMissing('services/old.jpg');
        $this->get('/services/group-hiit')->assertNotFound();

        $this->actingAs($admin, 'admin')->delete(route('admin.services.destroy', $service))->assertRedirect(route('admin.services.index'));
        $this->assertSame(0, Service::count());
    }
}
