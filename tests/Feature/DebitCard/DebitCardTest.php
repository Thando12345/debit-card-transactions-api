<?php

namespace Tests\Feature\DebitCard;

use Tests\TestCase;
use App\Models\User;
use App\Models\DebitCard;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DebitCardTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $validCardData;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->validCardData = [
            'card_number' => '4532123456789012',
            'expiry_month' => 12,
            'expiry_year' => 2025,
            'cvv' => '123',
            'card_holder_name' => 'John Doe'
        ];
    }

    public function test_user_can_create_debit_card()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/debit-cards', $this->validCardData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'card_number',
                    'expiry_month',
                    'expiry_year',
                    'card_holder_name',
                    'created_at'
                ]);

        $this->assertDatabaseHas('debit_cards', [
            'user_id' => $this->user->id,
            'card_number' => $this->validCardData['card_number'],
        ]);
    }

    public function test_user_cannot_create_debit_card_with_invalid_data()
    {
        Sanctum::actingAs($this->user);

        $invalidData = [
            'card_number' => '123', // Too short
            'expiry_month' => 13, // Invalid month
            'expiry_year' => 2020, // Past year
            'cvv' => '12', // Too short
            'card_holder_name' => ''
        ];

        $response = $this->postJson('/api/debit-cards', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'card_number',
                    'expiry_month',
                    'expiry_year',
                    'cvv',
                    'card_holder_name'
                ]);
    }

    public function test_user_can_list_their_debit_cards()
    {
        Sanctum::actingAs($this->user);
        
        DebitCard::factory()->count(3)->create(['user_id' => $this->user->id]);
        
        $response = $this->getJson('/api/debit-cards');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_user_cannot_see_others_debit_cards()
    {
        $otherUser = User::factory()->create();
        DebitCard::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/debit-cards');

        $response->assertStatus(200)
                ->assertJsonCount(0);
    }

    public function test_user_can_view_their_debit_card()
    {
        Sanctum::actingAs($this->user);
        
        $card = DebitCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/debit-cards/{$card->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $card->id,
                    'card_number' => $card->card_number
                ]);
    }

    public function test_user_cannot_view_others_debit_card()
    {
        $otherUser = User::factory()->create();
        $card = DebitCard::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/debit-cards/{$card->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_their_debit_card()
    {
        Sanctum::actingAs($this->user);
        
        $card = DebitCard::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'card_holder_name' => 'Updated Name',
            'expiry_month' => 11,
            'expiry_year' => 2026,
        ];

        $response = $this->putJson("/api/debit-cards/{$card->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson($updateData);

        $this->assertDatabaseHas('debit_cards', $updateData);
    }

    public function test_user_cannot_update_others_debit_card()
    {
        $otherUser = User::factory()->create();
        $card = DebitCard::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($this->user);

        $response = $this->putJson("/api/debit-cards/{$card->id}", [
            'card_holder_name' => 'Updated Name'
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_debit_card()
    {
        Sanctum::actingAs($this->user);
        
        $card = DebitCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/debit-cards/{$card->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('debit_cards', ['id' => $card->id]);
    }

    public function test_user_cannot_delete_others_debit_card()
    {
        $otherUser = User::factory()->create();
        $card = DebitCard::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/debit-cards/{$card->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('debit_cards', ['id' => $card->id]);
    }

    public function test_unauthorized_user_cannot_access_debit_cards()
    {
        $response = $this->getJson('/api/debit-cards');
        $response->assertStatus(401);

        $response = $this->postJson('/api/debit-cards', $this->validCardData);
        $response->assertStatus(401);
    }
}