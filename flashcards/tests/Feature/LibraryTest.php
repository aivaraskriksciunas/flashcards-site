<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LibraryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if new deck is added to user library.
     *
     * @return void
     */
    public function test_deck_added_to_library()
    {
        // Given
        $deck_author = User::factory()->create();
        $deck_name = 'Test';

        // When
        $response = $this->actingAs( $deck_author )
            ->postJson( '/api/decks', [
                'name' => $deck_name
            ]);

        // Assert
        $response->assertStatus( 201 );
        $library = $deck_author->getLibrary();
        $this->assertNotNull( $library, 'should retrieve library' );
        $this->assertEquals( 1, $library->decks()->count(), 'library should have one deck' );

        $deck = $library->decks()->first();
        $this->assertEquals( $deck_name, $deck->name, 'library deck name should match' );
    }
}
