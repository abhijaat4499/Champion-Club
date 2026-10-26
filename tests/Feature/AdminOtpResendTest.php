<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminOtpResendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-10-22 10:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    private function login(): Admin
    {
        $admin = $this->admin();
        $this->post(route('admin.login.submit'), ['email' => 'admin@example.com', 'password' => 'secret-123'])->assertRedirect(route('admin.otp.form'));

        return $admin->fresh();
    }

    public function test_verify_page_shows_resend_button_with_countdown(): void
    {
        $this->login();

        $this->get(route('admin.otp.form'))->assertOk()
            ->assertSee('Resend code')
            ->assertSee('data-resend-wait="30"', false)
            ->assertSee('action="' . route('admin.otp.resend') . '"', false);

        Carbon::setTestNow(now()->addSeconds(31));
        $this->get(route('admin.otp.form'))->assertSee('data-resend-wait="0"', false);
    }

    public function test_resend_sends_new_code_and_old_code_stops_working(): void
    {
        $admin = $this->login();
        $oldCode = (string) $admin->otp_code;

        Carbon::setTestNow(now()->addMinutes(11));
        $this->post(route('admin.otp.verify'), ['otp' => $oldCode])->assertSessionHasErrors('otp');

        $this->post(route('admin.otp.resend'))
            ->assertRedirect(route('admin.otp.form'))
            ->assertSessionHas('success');

        $admin->refresh();
        Mail::assertSent(OtpMail::class, 2);
        $this->assertTrue($admin->otp_expires_at->equalTo(now()->addMinutes(10)));

        $this->assertNotSame($oldCode, (string) $admin->otp_code);
        $this->post(route('admin.otp.verify'), ['otp' => $oldCode])->assertSessionHasErrors('otp');

        $this->post(route('admin.otp.verify'), ['otp' => (string) $admin->otp_code])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_resend_is_blocked_for_30_seconds(): void
    {
        $admin = $this->login();
        $code = $admin->otp_code;

        $this->post(route('admin.otp.resend'))->assertSessionHasErrors('otp');

        $this->assertSame($code, $admin->fresh()->otp_code);
        Mail::assertSent(OtpMail::class, 1);
    }

    public function test_resend_without_login_step_goes_to_login(): void
    {
        $this->post(route('admin.otp.resend'))->assertRedirect(route('admin.login'));
        Mail::assertNothingSent();
    }

    public function test_password_reset_code_can_be_resent(): void
    {
        $admin = $this->admin();
        $this->post(route('admin.password.email'), ['email' => 'admin@example.com'])->assertRedirect(route('admin.password.otp'));
        $oldCode = (string) $admin->fresh()->reset_otp_code;

        $this->get(route('admin.password.otp'))->assertOk()->assertSee('Resend code')->assertSee('action="' . route('admin.password.resend') . '"', false);

        Carbon::setTestNow(now()->addMinutes(2));
        $this->post(route('admin.password.resend'))->assertRedirect(route('admin.password.otp'))->assertSessionHas('success');

        $admin->refresh();
        Mail::assertSent(OtpMail::class, 2);

        $this->assertNotSame($oldCode, (string) $admin->reset_otp_code);
        $this->post(route('admin.password.verify'), ['otp' => $oldCode])->assertSessionHasErrors('otp');

        $this->post(route('admin.password.verify'), ['otp' => (string) $admin->reset_otp_code])->assertRedirect(route('admin.password.reset'));
    }
}
