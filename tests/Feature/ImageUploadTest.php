<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Service;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function dimensions(string $path): array
    {
        return array_slice(getimagesizefromstring(Storage::disk('public')->get($path)), 0, 2);
    }

    public function test_trainer_photo_is_resized_and_converted_to_webp(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.trainers.store'), [
                'name' => 'Coach Big Photo', 'role' => 'Coach', 'bio' => 'Bio.', 'display_order' => 1,
                'photo' => UploadedFile::fake()->image('phone-photo.jpg', 3000, 4000)->size(12000),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.trainers.index'));

        $trainer = Trainer::sole();
        $this->assertStringEndsWith('.webp', $trainer->photo);
        $this->assertSame([750, 1000], $this->dimensions($trainer->photo));
    }

    public function test_service_image_is_resized_and_small_images_are_not_enlarged(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $data = ['title' => 'Group HIIT', 'icon' => 'stopwatch', 'summary' => 'x', 'description' => 'y', 'display_order' => 1, 'is_active' => '1'];

        $this->actingAs($admin, 'admin')
            ->post(route('admin.services.store'), $data + ['image' => UploadedFile::fake()->image('wide.png', 4800, 2700)])
            ->assertSessionHasNoErrors();

        $service = Service::sole();
        $this->assertStringEndsWith('.webp', $service->image);
        $this->assertSame([1600, 900], $this->dimensions($service->image));

        $this->actingAs($admin, 'admin')
            ->put(route('admin.services.update', $service), $data + ['slug' => 'group-hiit', 'image' => UploadedFile::fake()->image('tiny.jpg', 400, 300)])
            ->assertSessionHasNoErrors();

        $this->assertSame([400, 300], $this->dimensions($service->fresh()->image));
    }

    public function test_photos_up_to_25_mb_are_accepted_and_larger_ones_get_a_clear_message(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $data = ['name' => 'Coach', 'role' => 'Coach', 'bio' => 'Bio.', 'display_order' => 1];

        $this->actingAs($admin, 'admin')
            ->post(route('admin.trainers.store'), $data + ['photo' => UploadedFile::fake()->image('ok.jpg', 800, 1000)->size(24000)])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.trainers.store'), $data + ['photo' => UploadedFile::fake()->image('huge.jpg', 800, 1000)->size(30000)])
            ->assertSessionHasErrors(['photo' => 'This photo is larger than 25 MB. Please choose a smaller one.']);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.trainers.store'), $data + ['photo' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf')])
            ->assertSessionHasErrors(['photo' => 'Please choose a JPG, PNG or WebP photo.']);
    }

    public function test_upload_larger_than_server_limit_redirects_back_with_message(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->from(route('admin.gallery.create'))
            ->call('POST', route('admin.gallery.store'), [], [], [], ['CONTENT_LENGTH' => 5 * 1024 * 1024 * 1024])
            ->assertRedirect(route('admin.gallery.create'))
            ->assertSessionHasErrors('upload');
    }

    public function test_admin_forms_explain_automatic_compression(): void
    {
        $admin = $this->admin();

        foreach (['admin.trainers.create', 'admin.services.create', 'admin.facilities.create', 'admin.about-sections.create'] as $route) {
            $this->actingAs($admin, 'admin')->get(route($route))->assertOk()->assertSee('resized and compressed automatically');
        }
    }
}
