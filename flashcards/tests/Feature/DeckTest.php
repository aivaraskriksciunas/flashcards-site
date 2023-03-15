<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Deck;

class DeckTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_cannot_create_deck_unauthenticated()
    {
        $response = $this->postJson( '/api/decks', [
            'name' => 'Test'
        ]);

        $response->assertUnauthorized();
    }

    public function test_creates_empty_deck()
    {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )
            ->postJson( '/api/decks', [
                'name' => 'Test'
            ]);

        $response->assertStatus( 201 );
        $this->assertDatabaseHas( 'decks', [
            'name' => 'Test'
        ]);
    }

    public function test_view_created_deck()
    {
        $deck_author = User::factory()->create();
        $deck = new Deck([ 'name' => 'Testing' ]);
        $deck->user()->associate( $deck_author );
        $deck->save();

        $response = $this->actingAs( $deck_author )
            ->getJson( "/api/decks/{$deck->id}" );
        
        $response->assertStatus( 200 );
    }

    public function test_cannot_view_created_deck()
    {
        $deck_author = User::factory()->create();
        $deck = new Deck([ 'name' => 'Testing' ]);
        $deck->user()->associate( $deck_author );
        $deck->save();

        $user = User::factory()->create();
        $response = $this->actingAs( $user )
            ->getJson( "/api/decks/{$deck->id}" );
        
        $response->assertStatus( 404 );
    }
}
