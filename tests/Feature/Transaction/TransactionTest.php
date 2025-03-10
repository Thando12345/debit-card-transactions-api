<?php

namespace Tests\Feature\Transaction;

use Tests\TestCase;
use App\Models\User;
use App\Models\DebitCard;
use App\Models\Transaction;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $debitCard;
    private $validTransactionData;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->debitCard = DebitCard::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        $this->validTransactionData = [
            'debit_card_id' => $this->debitCard->id,
            'amount' => 100.50,
            'type' => 'payment',
            'description' => 'Online Purchase',
            'merchant' => 'Amazon'
        ];
    }

    public function test_user_can_create_transaction()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/transactions', $this->validTransactionData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'debit_card_id',
                    'amount',
                    'type',
                    'description',
                    'merchant',
                    'created_at'
                ]);

        $this->assertDatabaseHas('transactions', [
            'debit_card_id' => $this->debitCard->id,
            'amount' => 100.50,
            'type' => 'payment'
        ]);
    }

    public function test_user_cannot_create_transaction_with_invalid_data()
    {
        Sanctum::actingAs($this->user);

        $invalidData = [
            'debit_card_id' => $this->debitCard->id,
            'amount' => -50, // Negative amount
            'type' => 'invalid_type',
            'description' => '',
            'merchant' => ''
        ];

        $response = $this->postJson('/api/transactions', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'amount',
                    'type',
                    'description',
                    'merchant'
                ]);
    }

    public function test_user_cannot_create_transaction_for_others_card()
    {
        $otherUser = User::factory()->create();
        $otherCard = DebitCard::factory()->create([
            'user_id' => $otherUser->id
        ]);

        Sanctum::actingAs($this->user);

        $invalidTransactionData = array_merge($this->validTransactionData, [
            'debit_card_id' => $otherCard->id
        ]);

        $response = $this->postJson('/api/transactions', $invalidTransactionData);

        $response->assertStatus(403);
    }

    public function test_user_can_list_their_transactions()
    {
        Sanctum::actingAs($this->user);
        
        Transaction::factory()->count(3)->create([
            'debit_card_id' => $this->debitCard->id
        ]);
        
        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_user_cannot_see_others_transactions()
    {
        $otherUser = User::factory()->create();
        $otherCard = DebitCard::factory()->create([
            'user_id' => $otherUser->id
        ]);
        Transaction::factory()->create([
            'debit_card_id' => $otherCard->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200)
                ->assertJsonCount(0);
    }

    public function test_user_can_view_their_transaction()
    {
        Sanctum::actingAs($this->user);
        
        $transaction = Transaction::factory()->create([
            'debit_card_id' => $this->debitCard->id
        ]);

        $response = $this->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $transaction->id,
                    'debit_card_id' => $this->debitCard->id
                ]);
    }

    public function test_user_cannot_view_others_transaction()
    {
        $otherUser = User::factory()->create();
        $otherCard = DebitCard::factory()->create([
            'user_id' => $otherUser->id
        ]);
        $transaction = Transaction::factory()->create([
            'debit_card_id' => $otherCard->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_access_transactions()
    {
        $response = $this->getJson('/api/transactions');
        $response->assertStatus(401);

        $response = $this->postJson('/api/transactions', $this->validTransactionData);
        $response->assertStatus(401);
    }

    public function test_transaction_amount_must_be_positive()
    {
        Sanctum::actingAs($this->user);

        $invalidData = array_merge($this->validTransactionData, [
            'amount' => -100
        ]);

        $response = $this->postJson('/api/transactions', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['amount']);
    }

    public function test_transaction_type_must_be_valid()
    {
        Sanctum::actingAs($this->user);

        $invalidData = array_merge($this->validTransactionData, [
            'type' => 'invalid_type'
        ]);

        $response = $this->postJson('/api/transactions', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['type']);
    }
}