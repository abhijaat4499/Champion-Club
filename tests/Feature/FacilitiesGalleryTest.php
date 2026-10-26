<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Facility;
use App\Models\GalleryPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacilitiesGalleryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function photo(array $overrides = []): GalleryPhoto
    {
        return GalleryPhoto::create(array_merge([
            'image' => 'gallery/a.webp', 'width' => 1400, 'height' => 933,
            'alt' => 'Reception desk', 'caption' => 'Reception', 'is_active' => true, 'display_order' => 1,
        ], $overrides));
    }

    public function test_guest_cannot_manage_facilities_or_gallery(): void
    {
        foreach (['/admin/facilities', '/admin/facilities/create', '/admin/gallery', '/admin/gallery/create'] as $url) {
            $this->get($url)->assertRedirect(route('admin.login'));
        }

        $this->post('/admin/gallery/reorder', ['ids' => [1]])->assertRedirect(route('admin.login'));
    }

    public function test_facilities_page_and_home_show_only_active_facilities(): void
    {
        Facility::create(['title' => 'Cardio zone', 'text' => 'Treadmills and bikes.', 'is_active' => true, 'display_order' => 1]);
        Facility::create(['title' => 'Secret Room', 'text' => 'Hidden.', 'is_active' => false, 'display_order' => 2]);

        $this->get('/facilities')->assertOk()->assertSee('Cardio zone')->assertSee('Treadmills and bikes.')->assertDontSee('Secret Room');
        $this->get('/')->assertOk()->assertSee('Cardio zone')->assertDontSee('Secret Room');
    }

    public function test_facilities_page_handles_no_facilities(): void
    {
        $this->get('/facilities')->assertOk()->assertSee('Facility details will be added soon.');
    }

    public function test_admin_can_add_edit_and_delete_facility_with_photo(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.facilities.store'), [
                'title' => 'Steam room', 'text' => 'Relax after training.', 'display_order' => 3,
                'image_position' => 'upper', 'is_active' => '1',
                'image' => UploadedFile::fake()->image('steam.jpg', 2400, 1600),
            ])
            ->assertRedirect(route('admin.facilities.index'));

        $facility = Facility::sole();
        $this->assertStringEndsWith('.webp', $facility->image);
        Storage::disk('public')->assertExists($facility->image);
        [$width] = getimagesizefromstring(Storage::disk('public')->get($facility->image));
        $this->assertSame(1600, $width);

        $this->get('/facilities')->assertSee('Steam room')->assertSee('object-position: 50% 25%', false);

        $old = $facility->image;
        $this->actingAs($admin, 'admin')
            ->put(route('admin.facilities.update', $facility), [
                'title' => 'Steam & sauna', 'text' => 'Updated.', 'display_order' => 1, 'remove_image' => '1',
            ])
            ->assertRedirect(route('admin.facilities.index'));

        $facility->refresh();
        $this->assertSame('Steam & sauna', $facility->title);
        $this->assertNull($facility->image);
        $this->assertFalse($facility->is_active);
        Storage::disk('public')->assertMissing($old);

        $this->actingAs($admin, 'admin')->delete(route('admin.facilities.destroy', $facility))->assertRedirect(route('admin.facilities.index'));
        $this->assertSame(0, Facility::count());
    }

    public function test_navbar_links_to_gallery_and_page_lists_active_photos(): void
    {
        $this->photo();
        $this->photo(['alt' => 'Private photo', 'caption' => 'Hidden one', 'is_active' => false, 'display_order' => 2]);

        $this->get('/')->assertSee(route('gym.gallery'))->assertSee('>Gallery</a>', false);

        $this->get('/gallery')
            ->assertOk()
            ->assertSee('alt="Reception desk"', false)
            ->assertSee('width="1400" height="933"', false)
            ->assertSee('Reception')
            ->assertSee('data-lightbox', false)
            ->assertDontSee('Private photo');

        $this->get('/sitemap.xml')->assertSee(route('gym.gallery'));
    }

    public function test_gallery_page_handles_no_photos(): void
    {
        $this->get('/gallery')->assertOk()->assertSee('Photos will be added soon.')->assertDontSee('data-lightbox', false);
    }

    public function test_admin_uploads_multiple_photos_resized_to_webp(): void
    {
        Storage::fake('public');
        $this->photo(['display_order' => 5]);

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.gallery.store'), [
                'photos' => [
                    UploadedFile::fake()->image('cardio-section.jpg', 3000, 2000),
                    UploadedFile::fake()->image('lockers_area.png', 800, 1200),
                ],
                'alt' => '',
                'caption' => 'New photos',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.gallery.index'))
            ->assertSessionHas('success', '2 photos uploaded.');

        $uploaded = GalleryPhoto::where('caption', 'New photos')->orderBy('display_order')->get();
        $this->assertCount(2, $uploaded);
        $this->assertSame([6, 7], $uploaded->pluck('display_order')->all());
        $this->assertSame('Cardio Section', $uploaded[0]->alt);
        $this->assertSame('Lockers Area', $uploaded[1]->alt);
        $this->assertSame([1400, 933], [$uploaded[0]->width, $uploaded[0]->height]);
        $this->assertSame([800, 1200], [$uploaded[1]->width, $uploaded[1]->height]);
        $this->assertTrue($uploaded->every(fn ($p) => str_ends_with($p->image, '.webp') && Storage::disk('public')->exists($p->image)));
    }

    public function test_upload_rejects_non_images_and_missing_files(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->post(route('admin.gallery.store'), [])->assertSessionHasErrors('photos');
        $this->actingAs($admin, 'admin')
            ->post(route('admin.gallery.store'), ['photos' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')]])
            ->assertSessionHasErrors('photos.0');

        $this->assertSame(0, GalleryPhoto::count());
    }

    public function test_admin_can_edit_reorder_and_bulk_delete_photos(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $a = $this->photo(['image' => 'gallery/a.webp', 'display_order' => 1]);
        $b = $this->photo(['image' => 'gallery/b.webp', 'alt' => 'Lockers', 'display_order' => 2]);
        $c = $this->photo(['image' => 'gallery/c.webp', 'alt' => 'Cardio', 'display_order' => 3]);
        foreach (['a', 'b', 'c'] as $name) {
            Storage::disk('public')->put("gallery/{$name}.webp", 'x');
        }

        $this->actingAs($admin, 'admin')
            ->put(route('admin.gallery.update', $a), ['alt' => 'Front reception', 'caption' => ''])
            ->assertRedirect(route('admin.gallery.index'));
        $this->assertSame('Front reception', $a->fresh()->alt);
        $this->assertFalse($a->fresh()->is_active);

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.gallery.reorder'), ['ids' => [$c->id, $a->id, $b->id]])
            ->assertOk()
            ->assertJson(['ok' => true]);
        $this->assertSame([1, 2, 3], [$c->fresh()->display_order, $a->fresh()->display_order, $b->fresh()->display_order]);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.gallery.bulk-destroy'), ['ids' => [$a->id, $b->id]])
            ->assertRedirect(route('admin.gallery.index'));
        $this->assertSame([$c->id], GalleryPhoto::pluck('id')->all());
        Storage::disk('public')->assertMissing('gallery/a.webp');
        Storage::disk('public')->assertExists('gallery/c.webp');

        $this->actingAs($admin, 'admin')->get(route('admin.gallery.index'))->assertOk()->assertSee('Cardio')->assertSee('Select all');
    }
}
