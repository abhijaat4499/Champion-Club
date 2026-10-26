<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContactLeadTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('secret-123')]);
    }

    public function test_contact_form_has_limited_time_offers_option(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('<option value="offer"', false)
            ->assertSee('Limited time offers');
    }

    public function test_claim_offer_link_preselects_offer_interest(): void
    {
        $this->get('/contact?interest=offer')
            ->assertSee('<option value="offer" selected>', false);
    }

    public function test_lead_with_offer_interest_is_saved(): void
    {
        $this->post('/contact', [
            'name' => 'Neha',
            'email' => 'neha@example.com',
            'phone' => '9876543210',
            'interest' => 'offer',
            'message' => 'I want the Diwali offer',
        ])->assertRedirect(route('gym.thankyou'));

        $this->assertSame('offer', Contact::firstOrFail()->interest);
    }

    public function test_thank_you_page_after_submit(): void
    {
        $this->followingRedirects()
            ->post('/contact', [
                'name' => 'Neha',
                'email' => 'neha@example.com',
                'message' => 'Hello',
            ])
            ->assertOk()
            ->assertSee('Thank you!')
            ->assertSee('Our team will get back to you shortly.')
            ->assertSee('ChampionClub')
            ->assertSee('Back to home')
            ->assertSee('href="' . url('/') . '"', false)
            ->assertSee('Connect with us now')
            ->assertSee('https://wa.me/918700362410', false);
    }

    public function test_thank_you_page_without_submit_goes_home(): void
    {
        $this->get('/thank-you')->assertRedirect(route('gym.home'));
    }

    public function test_unknown_interest_is_rejected(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => 'X', 'email' => 'x@example.com', 'interest' => 'hacker', 'message' => 'hi',
        ])->assertSessionHasErrors('interest');

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_callable_number_normalises_indian_mobiles(): void
    {
        $cases = [
            '9876543210' => '09876543210',
            '07678210685' => '07678210685',
            '919876543210' => '09876543210',
            '98765 43210' => '09876543210',
            'rahul' => null,
            '12345' => null,
            '5876543210' => null,
            '' => null,
        ];

        foreach ($cases as $phone => $expected) {
            $this->assertSame($expected, (new Contact(['phone' => (string) $phone]))->callableNumber(), "phone: $phone");
        }
    }

    public function test_dashboard_shows_call_button_only_for_valid_numbers(): void
    {
        Contact::create(['name' => 'Valid', 'email' => 'v@example.com', 'phone' => '07678210685', 'interest' => 'offer', 'message' => 'hi']);
        Contact::create(['name' => 'Junk', 'email' => 'j@example.com', 'phone' => 'rahul', 'message' => 'hi']);

        $this->actingAs($this->admin(), 'admin')
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('href="tel:07678210685"', false)
            ->assertSee('Call Valid')
            ->assertDontSee('Call Junk')
            ->assertSee('rahul')
            ->assertSee('Limited time offers');
    }
}
