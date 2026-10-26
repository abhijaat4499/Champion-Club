<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('old-password'),
        ]);
    }

    private function lead(string $name, ?string $interest, string $createdAtUtc): Contact
    {
        $lead = new Contact(['name' => $name, 'email' => strtolower($name) . '@example.com', 'interest' => $interest, 'message' => 'Hi']);
        $lead->created_at = Carbon::parse($createdAtUtc, 'UTC');
        $lead->save();

        return $lead;
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
        $this->get('/admin/change-password')->assertRedirect(route('admin.login'));
    }

    public function test_dashboard_shows_sidebar_and_navbar(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('ChampionClub')
            ->assertSee('Dashboard')
            ->assertSee('Trainers')
            ->assertSee('Membership')
            ->assertSee('Settings')
            ->assertSee('Change password')
            ->assertSee('Logout')
            ->assertDontSee('Manage Plans');
    }

    public function test_date_filter_uses_india_time(): void
    {
        $this->lead('Late', 'trial', '2026-10-10 20:00:00');
        $this->lead('Early', 'trial', '2026-10-09 10:00:00');

        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?from=2026-10-11&to=2026-10-11')
            ->assertOk()
            ->assertSee('late@example.com')
            ->assertDontSee('early@example.com');

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?from=2026-10-09&to=2026-10-10')
            ->assertSee('early@example.com')
            ->assertDontSee('late@example.com');
    }

    public function test_sort_by_name_and_interest(): void
    {
        $this->lead('Charlie', null, '2026-10-01 10:00:00');
        $this->lead('Alice', 'trial', '2026-10-02 10:00:00');
        $this->lead('Bob', 'membership', '2026-10-03 10:00:00');

        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?sort=name_asc')
            ->assertSeeInOrder(['alice@example.com', 'bob@example.com', 'charlie@example.com']);

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?sort=name_desc')
            ->assertSeeInOrder(['charlie@example.com', 'bob@example.com', 'alice@example.com']);

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?sort=interest')
            ->assertSeeInOrder(['bob@example.com', 'alice@example.com', 'charlie@example.com']);

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?sort=oldest')
            ->assertSeeInOrder(['charlie@example.com', 'alice@example.com', 'bob@example.com']);

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard')
            ->assertSeeInOrder(['bob@example.com', 'alice@example.com', 'charlie@example.com']);
    }

    public function test_leads_are_paginated_after_25_rows(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $this->lead(sprintf('Lead%02d', $i), 'trial', Carbon::parse('2026-10-01 10:00:00')->addMinutes($i)->toDateTimeString());
        }

        $admin = $this->admin();

        $page1 = $this->actingAs($admin, 'admin')->get('/admin/dashboard?sort=name_asc&from=2026-09-01');
        $page1->assertOk()
            ->assertSee('lead01@example.com')
            ->assertSee('lead25@example.com')
            ->assertDontSee('lead26@example.com')
            ->assertSee('Showing 1–25 of 30 enquiries')
            ->assertSee('sort=name_asc', false)
            ->assertSee('from=2026-09-01', false)
            ->assertSee('page=2', false);

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard?sort=name_asc&from=2026-09-01&page=2')
            ->assertOk()
            ->assertSee('lead26@example.com')
            ->assertSee('lead30@example.com')
            ->assertDontSee('lead25@example.com')
            ->assertSee('Showing 26–30 of 30 enquiries');
    }

    public function test_no_heading_bar_or_stat_boxes_on_dashboard(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/dashboard')
            ->assertDontSee('Total Enquiries')
            ->assertDontSee('stat-card', false);
    }

    public function test_invalid_filter_is_rejected(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->from('/admin/dashboard')
            ->get('/admin/dashboard?from=2026-10-11&to=2026-10-01&sort=hack')
            ->assertSessionHasErrors(['to', 'sort']);
    }

    public function test_change_password_needs_matching_confirmation(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->from('/admin/change-password')
            ->put('/admin/change-password', ['password' => 'new-password-1', 'password_confirmation' => 'different'])
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('old-password', $admin->fresh()->password));
    }

    public function test_admin_can_change_password(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->get('/admin/change-password')
            ->assertOk()
            ->assertSee('New password');

        $this->actingAs($admin, 'admin')
            ->put('/admin/change-password', ['password' => 'new-password-1', 'password_confirmation' => 'new-password-1'])
            ->assertRedirect(route('admin.password.change'))
            ->assertSessionHas('success');

        $fresh = $admin->fresh();
        $this->assertTrue(Hash::check('new-password-1', $fresh->password));
        $this->assertFalse(Hash::check('old-password', $fresh->password));
    }

    public function test_other_admin_pages_use_panel_layout(): void
    {
        $admin = $this->admin();

        foreach (['/admin/plans', '/admin/plans/create', '/admin/trainers', '/admin/trainers/create'] as $url) {
            $this->actingAs($admin, 'admin')->get($url)->assertOk()->assertSee('admin-sidebar', false);
        }
    }
}
