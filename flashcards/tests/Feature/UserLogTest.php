<?php

namespace Tests\Feature;

use App\Models\Deck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserLog;

class UserLogTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    public function test_creates_log()
    {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )
            ->postJson( '/api/decks', [
                'name' => 'Test'
            ]);

        $response->assertStatus( 201 );
        $logs = UserLog::where( 'action', 'Deck created' )->get();
        $this->assertCount( 1, $logs );

        $log = UserLog::first();
        $this->assertEquals( Deck::first()->id, $logs->first()->object_id, 'Log should be tied to created deck.' );
    }
}
