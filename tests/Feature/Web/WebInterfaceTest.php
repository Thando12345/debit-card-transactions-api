<?php

namespace Tests\Feature\Web;

use Tests\TestCase;
use App\Models\User;
use App\Models\DebitCard;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebInterfaceTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $validUserData;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->validUserData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
    }

    public function test_registration_page_displays_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
                ->assertSee('Register')
                ->assertSee('Name')
                ->assertSee('Email')
                ->assertSee('Password');
    }

    public function test_registration_requires_csrf_token()
    {
        $response = $this->post('/register', $this->validUserData);

        $response->assertStatus(419); // CSRF token mismatch
    }

    public function test_user_can_register_through_web_form()
    {
        $response = $this->followingRedirects()
                        ->from('/register')
                        ->post('/register', $this->validUserData);

        $response->assertStatus(200)
                ->assertSee('Dashboard');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
    }

    public function test_login_page_displays_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
                ->assertSee('Login')
                ->assertSee('Email')
                ->assertSee('Password');
    }

    public function test_user_can_login_through_web_form()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->followingRedirects()
                        ->from('/login')
                        ->post('/login', [
                            'email' => 'test@example.com',
                            'password' => 'password123',
                        ]);

        $response->assertStatus(200)
                ->assertSee('Dashboard');

        $this->assertAuthenticated();
    }

    public function test_dashboard_shows_empty_state()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->get('/dashboard');

        $response->assertStatus(200)
                ->assertSee('No debit cards found')
                ->assertSee('No recent transactions');
    }

    public function test_dashboard_displays_user_debit_cards()
    {
        $user = User::factory()->create();
        $card = DebitCard::factory()->create([
            'user_id' => $user->id,
            'card_holder_name' => 'John Doe',
            'card_number' => '4532123456789012'
        ]);

        $response = $this->actingAs($user)
                        ->get('/dashboard');

        $response->assertStatus(200)
                ->assertSee('John Doe')
                ->assertSee('4532********9012');
    }

    public function test_dashboard_displays_recent_transactions()
    {
        $user = User::factory()->create();
        $card = DebitCard::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create([
            'debit_card_id' => $card->id,
            'amount' => 100.50,
            'merchant' => 'Test Store'
        ]);

        $response = $this->actingAs($user)
                        ->get('/dashboard');

        $response->assertStatus(200)
                ->assertSee('Test Store')
                ->assertSee('100.50');
    }

    public function test_debit_card_form_requires_validation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->from('/debit-cards/create')
                        ->post('/debit-cards', [
                            'card_number' => 'invalid',
                            'expiry_month' => 13,
                            'expiry_year' => 2020,
                        ]);

        $response->assertRedirect('/debit-cards/create')
                ->assertSessionHasErrors(['card_number', 'expiry_month', 'expiry_year']);
    }

    public function test_successful_card_creation_shows_success_message()
    {
        $user = User::factory()->create();
        $validCardData = [
            'card_number' => '4532123456789012',
            'expiry_month' => 12,
            'expiry_year' => 2025,
            'cvv' => '123',
            'card_holder_name' => 'John Doe'
        ];

        $response = $this->actingAs($user)
                        ->from('/debit-cards/create')
                        ->post('/debit-cards', $validCardData);

        $response->assertRedirect('/dashboard')
                ->assertSessionHas('success', 'Debit card created successfully');
    }

    public function test_card_deletion_requires_confirmation()
    {
        $user = User::factory()->create();
        $card = DebitCard::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
                        ->get("/debit-cards/{$card->id}/delete");

        $response->assertStatus(200)
                ->assertSee('Are you sure you want to delete this card?')
                ->assertSee('This action cannot be undone.');
    }

    public function test_real_time_transaction_updates()
    {
        $user = User::factory()->create();
        $card = DebitCard::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
                        ->get('/dashboard');

        $response->assertStatus(200)
                ->assertSee('data-transaction-update-url');
    }
}