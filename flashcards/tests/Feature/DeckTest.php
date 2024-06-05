<?php

namespace Tests\Feature;

use App\Enums\FlashcardType;
use App\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Deck;
use App\Models\Flashcard;

class DeckTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    /**
     * Can create a deck for this user unauthorized
     *
     * @return void
     */
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

    /**
     * User can view this deck
     *
     * @return void
     */
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

    /**
     * Admin user can view any deck
     *
     * @return void
     */
    public function test_admin_can_view_any_deck() 
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create([ 'account_type' => UserType::ADMIN ]);
        $deck = $u1->decks()->create([ 'name' => 'Testing' ]);

        $response = $this->actingAs( $u2 )
            ->getJson( "/api/decks/{$deck->id}" );

        $response->assertStatus( 200 );
    }

    /**
     * Unauthorized user can view deck
     *
     * @return boolean
     */
    public function test_can_view_deck_unauthorized()
    {
        $deck = User::factory()->create()
            ->decks()->create([ 'name' => 'Test' ]);

        $response = $this->getJson( "/api/decks/{$deck->id}" );

        $response->assertStatus( 200 );
    }

    /**
     * Can add new cards to empty deck
     *
     * @return void
     */
    public function test_can_edit_deck()
    {
        $user = User::factory()->create();
        $deck = $user->decks()->create([ 'name' => 'Test' ]);

        $request = $this->actingAs( $user )
            ->patchJson( 
                route( 'api.decks.update', [ 'deck' => $deck ] ),
            [
                'name' => 'Test2',
                'cards' => [
                    [ 'question' => 'A', 'answer' => 'B' ],
                    [ 'question' => 'C', 'answer' => 'D' ],
                ]
            ] 
        );

        $request->assertSuccessful();

        $deck->refresh();
        $this->assertEquals( 2, $deck->cards()->count() );
        $this->assertEquals( 'Test2', $deck->name );
    }

    /**
     * Test correctly updates existing, deletes cards
     *
     * @return void
     */
    public function test_correctly_updates_cards()
    {
        $user = User::factory()->create();
        $deck = $user->decks()->create([ 'name' => 'Test' ]);
        $cards = $deck->cards()->createMany([
            [ 'question' => 'A', 'answer' => 'B' ],
            [ 'question' => 'C', 'answer' => 'D' ]
        ]);

        $request = $this->actingAs( $user )
            ->patchJson( 
                route( 'api.decks.update', [ 'deck' => $deck ] ),
            [
                'name' => 'Test',
                'cards' => [
                    [ 'id' => $cards[0]->id, 'question' => 'A', 'answer' => 'B' ],
                    [ 'id' => $cards[1]->id, 'question' => 'Updated', 'answer' => 'Updated Answer' ],
                    [ 'question' => 'New', 'answer' => 'New Answer' ],
                ]
            ] 
        );

        $request->assertSuccessful();

        $deck->refresh();
        $new_cards = $deck->cards()->get();
        $this->assertCount( 3, $new_cards );

        $card = Flashcard::find( $cards[0]->id );
        $this->assertNotNull( $card, 'Untouched card should still exist' );
        $this->assertEquals( 'A', $card->question, 'Untouched card should not have modifications' );
        $this->assertEquals( 'B', $card->answer );

        $card = Flashcard::find( $cards[1]->id );
        $this->assertNull( $card, 'Touched card should no longer exist and a new one should have been created' );
    }

    /**
     * Test unauthorized user cannot modify deck
     *
     * @return void
     */
    public function test_unauthorized_user_cannot_modify()
    {
        $user = User::factory()->create();
        $deck = $user->decks()->create([ 'name' => 'Test' ]);

        $request = $this->patchJson( 
            route( 'api.decks.update', [ 'deck' => $deck ] ),
            [
                'name' => 'Test2',
                'cards' => [
                    [ 'question' => 'A', 'answer' => 'B' ],
                    [ 'question' => 'C', 'answer' => 'D' ],
                ]
            ] 
        );

        $request->assertUnauthorized();

        $deck->refresh();
        $this->assertEquals( 0, $deck->cards()->count() );
        $this->assertEquals( 'Test', $deck->name );
    }

    /**
     * Test non owner cannot modify deck
     *
     * @return void
     */
    public function test_non_owner_user_cannot_modify()
    {
        $user = User::factory()->create();
        $u2 = User::factory()->create();
        $deck = $user->decks()->create([ 'name' => 'Test' ]);

        $request = $this->actingAs( $u2 )->patchJson( 
            route( 'api.decks.update', [ 'deck' => $deck ] ),
            [
                'name' => 'Test2',
                'cards' => [
                    [ 'question' => 'A', 'answer' => 'B' ],
                    [ 'question' => 'C', 'answer' => 'D' ],
                ]
            ] 
        );

        $request->assertNotFound();

        $deck->refresh();
        $this->assertEquals( 0, $deck->cards()->count() );
        $this->assertEquals( 'Test', $deck->name );
    }

    /**
     * Test admin can modify deck
     *
     * @return void
     */
    public function test_admin_user_can_modify()
    {
        $user = User::factory()->create();
        $u2 = User::factory()->create([ 'account_type' => UserType::ADMIN ]);
        $deck = $user->decks()->create([ 'name' => 'Test' ]);

        $request = $this->actingAs( $u2 )->patchJson( 
            route( 'api.decks.update', [ 'deck' => $deck ] ),
            [
                'name' => 'Test2',
                'cards' => [
                    [ 'question' => 'A', 'answer' => 'B' ],
                    [ 'question' => 'C', 'answer' => 'D' ],
                ]
            ] 
        );

        $request->assertSuccessful();

        $deck->refresh();
        $this->assertEquals( 2, $deck->cards()->count() );
        $this->assertEquals( 'Test2', $deck->name );
    }

    public function test_sets_and_updates_flashcard_types()
    {
        $user = User::factory()->create();

        $request = $this->actingAs( $user )->postJson( 
            route( 'api.decks.create' ),
            [
                'name' => 'Test2',
                'cards' => [
                    [ 'question' => 'A', 'answer' => 'B', 'question_type' => FlashcardType::Text, 'answer_type' => FlashcardType::List ],
                    [ 'question' => 'C', 'answer' => 'D', 'question_type' => FlashcardType::List, 'answer_type' => FlashcardType::Text ],
                ]
            ] 
        );

        $request->assertSuccessful();

        $deck = Deck::first();
        $cards = $deck->cards()->get();
        $this->assertEquals( FlashcardType::List, $cards[0]->answer_type, 'Flashcard answer type should be set' );
        $this->assertEquals( FlashcardType::Text, $cards[0]->question_type, 'Flashcard question type should be set' );
        $this->assertEquals( FlashcardType::List, $cards[1]->question_type, 'Flashcard question type should be set' );
        $this->assertEquals( FlashcardType::Text, $cards[1]->answer_type, 'Flashcard answer type should be set' );

        // Test upddate
        $request = $this->actingAs( $user )->patchJson( 
            route( 'api.decks.update', $deck ),
            [
                'name' => 'Test2',
                'cards' => [
                    [ 'question' => 'A', 'answer' => 'B', 'question_type' => FlashcardType::List, 'answer_type' => FlashcardType::Text ],
                    [ 'question' => 'C', 'answer' => 'D', 'question_type' => FlashcardType::Text, 'answer_type' => FlashcardType::List ],
                ]
            ] 
        );

        $request->assertSuccessful();

        $deck->refresh();
        $cards = $deck->cards()->get();
        $this->assertEquals( FlashcardType::List, $cards[1]->answer_type, 'Flashcard answer type should be updated' );
        $this->assertEquals( FlashcardType::Text, $cards[1]->question_type, 'Flashcard question type should be updated' );
        $this->assertEquals( FlashcardType::List, $cards[0]->question_type, 'Flashcard question type should be updated' );
        $this->assertEquals( FlashcardType::Text, $cards[0]->answer_type, 'Flashcard answer type should be updated' );
    }

    public function test_validates_flashcard_types()
    {
        $user = User::factory()->create();

        $this->actingAs( $user )->postJson( 
            route( 'api.decks.create' ),
            [
                'name' => 'Test2',
                'cards' => [ [ 'question' => 'A', 'answer' => 'B', 'question_type' => 'lala', 'answer_type' => FlashcardType::List ] ]
            ] 
        )->assertUnprocessable();

        $this->actingAs( $user )->postJson( 
            route( 'api.decks.create' ),
            [
                'name' => 'Test2',
                'cards' => [ [ 'question' => 'A', 'answer' => 'B', 'question_type' => FlashcardType::List, 'answer_type' => 'lala' ] ]
            ] 
        )->assertUnprocessable();

        $this->assertDatabaseCount( 'flashcards', 0 );

        // Test updating
        $deck = $user->decks()->create([ 'name' => 'Test' ]);
        $this->actingAs( $user )->patchJson( 
            route( 'api.decks.update', $deck ),
            [
                'name' => 'Test2',
                'cards' => [ [ 'question' => 'A', 'answer' => 'B', 'question_type' => FlashcardType::List, 'answer_type' => 'lala' ] ]
            ] 
        )->assertUnprocessable();
        $this->actingAs( $user )->patchJson( 
            route( 'api.decks.update', $deck ),
            [
                'name' => 'Test2',
                'cards' => [ [ 'question' => 'A', 'answer' => 'B', 'question_type' => 'lalala', 'answer_type' => FlashcardType::Text ] ]
            ] 
        )->assertUnprocessable();
    }  

    public function test_deletes_deck()
    {
        $owner = User::factory()->create();
        $deck = Deck::factory()->for( $owner )->create();
        $cards = Flashcard::factory()->for( $deck )->createMany( 10 );

        $response = $this->actingAs( $owner )->deleteJson(
            route( 'api.decks.delete', $deck )
        );
        $response->assertSuccessful();

        $deck->refresh();
        $this->assertNotNull( $deck->deleted_at, 'Should be marked as deleted' );
    }

    public function test_nonowner_does_not_delete_deck()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $deck = Deck::factory()->for( $owner )->create();
        $cards = Flashcard::factory()->for( $deck )->createMany( 10 );

        $response = $this->actingAs( $user )->deleteJson(
            route( 'api.decks.delete', $deck )
        );
        $response->assertNotFound();

        $deck->refresh();
        $this->assertNull( $deck->deleted_at, 'Should not be marked as deleted' );

        $response = $this->deleteJson(
            route( 'api.decks.delete', $deck )
        );
        $response->assertNotFound();

        $deck->refresh();
        $this->assertNull( $deck->deleted_at, 'Should not be marked as deleted' );
    }

    public function test_adds_to_library()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();

        $deck = Deck::factory()->for( $owner )->create();
        $cards = Flashcard::factory()->for( $deck )->createMany( 10 );

        $response = $this->actingAs( $user )->postJson(
            route( 'api.decks.add-to-library', $deck )
        );
        $response->assertSuccessful();

        $deck->refresh();
        $this->assertEquals( 1, $user->getLibrary()->decks->count(), 'Deck should be added to library' );
        $this->assertEquals( $owner->id, $deck->user_id, 'Owner should not have changed' );

        // Check what happens when you add twice
        $response = $this->actingAs( $user )->postJson(
            route( 'api.decks.add-to-library', $deck )
        );
        $response->assertSuccessful();

        $this->assertEquals( 0, $user->getLibrary()->decks->count(), 'Deck should have been removed' );
    }

    public function test_creates_copy()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();

        $deck = Deck::factory()->for( $owner )->create();
        $card_amount = $this->faker->numberBetween( 10, 20 );
        $cards = Flashcard::factory()->for( $deck )->createMany( $card_amount );

        $response = $this->actingAs( $user )->postJson(
            route( 'api.decks.copy', $deck )
        );
        $response->assertSuccessful();

        $deck->refresh();
        $this->assertEquals( $owner->id, $deck->user_id, 'Owner should not have changed' );
        $this->assertCount( $card_amount, $deck->cards, 'Number of cards should remain the same' );

        $this->assertEquals( 1, $user->decks()->count(), 'User should have another deck' );

        $copied_deck = $user->decks->first();
        $this->assertCount( $card_amount, $copied_deck->cards, 'All cards should be copied' );
        $this->assertCount( 1, $user->getLibrary()->decks, 'Deck should be added to library' );
    }
}
