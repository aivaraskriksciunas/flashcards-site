<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_mode_flips_answers()
    {
        $user = User::factory()->create();
        $deck = $user->decks()->create([ 'name' => 'Test' ]);
        $flashcard = Flashcard::factory()->make();
        $deck->cards()->save( $flashcard );

        $response = $this->actingAs( $user )->getJson( route( 'api.quiz.generate', [ 'deck' => $deck, 'quiz-mode' => 'aq' ] ) );
        $response->assertStatus( 201 );
        $json = $response->json();
        $this->assertEquals( $flashcard->answer, $json['items'][0]['card']['question'], 'Answer and question should be flipped.' );
        $this->assertEquals( $flashcard->question, $json['items'][0]['card']['answer'], 'Answer and question should be flipped.' );
    }

    public function test_quiz_does_not_flip_answers_by_default()
    {
        $user = User::factory()->create();
        $deck = $user->decks()->create([ 'name' => 'Test' ]);
        $flashcard = Flashcard::factory()->make();
        $deck->cards()->save( $flashcard );

        $response = $this->actingAs( $user )->getJson( route( 'api.quiz.generate', [ 'deck' => $deck ] ) );
        $response->assertStatus( 201 );
        $json = $response->json();
        $this->assertEquals( $flashcard->question, $json['items'][0]['card']['question'], 'Answer and question should not be flipped.' );
        $this->assertEquals( $flashcard->answer, $json['items'][0]['card']['answer'], 'Answer and question should not be flipped.' );
    }
}
