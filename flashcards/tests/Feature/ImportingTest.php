<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing simple quizlet import
     *
     * @return void
     */
    public function test_quizlet_import()
    {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )->postJson(
            route( 'api.import-quizlet' ),
            [ 'content' => "Word\tAnswer\nWord 2\tAnother answer" ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'flashcards', 2 );
    }
}
