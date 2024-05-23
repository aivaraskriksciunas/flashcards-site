<?php

namespace Tests\Feature;

use App\Mail\PasswordResetEmail;
use App\Models\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public User $user;

    public function test_can_reset_password()
    {
        Mail::fake();

        // 1. Create password reset
        $response = $this->actingAs( $this->user )->postJson( 
            route( 'api.forgot-password' ),
            [ 'email' => $this->user->email ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'password_resets', 1 );

        Mail::assertSent( PasswordResetEmail::class );

        // 2. Reset password
        $reset = PasswordReset::first();
        $this->assertGreaterThanOrEqual( 20, strlen( $reset->code ), 'Reset code should be at least 20 characters long.' );
        
        $new_pass = $this->faker->password( 12 );
        $response = $this->actingAs( $this->user )->postJson(
            route( 'api.reset-password', $reset->code ),
            [ 'password' => $new_pass, 'password_confirmation' => $new_pass ]
        );

        $response->assertSuccessful();

        // 3. Make sure user can authenticate with new password
        $response = $this->actingAs( $this->user )->postJson(
            route( 'api.login' ),
            [ 'email' => $this->user->email, 'password' => $new_pass ]
        );

        $this->user->refresh();
        $this->assertNotEquals( $this->user->password, $new_pass, 'Ensure password is hashed' );
        $response->assertSuccessful();
    }

    public function test_expired_reset_does_not_work()
    {
        Mail::fake();

        // 1. Create expired password reset
        $reset = PasswordReset::createForUser( $this->user );
        $reset->expires_at = Carbon::now()->subHour();
        $reset->save();

        // 2. Reset password        
        $new_pass = $this->faker->password( 12 );
        $response = $this->actingAs( $this->user )->postJson(
            route( 'api.reset-password', $reset->code ),
            [ 'password' => $new_pass, 'password_confirmation' => $new_pass ]
        );

        $response->assertNotFound();

        // 3. Make sure user cannot authenticate with new password
        $response = $this->actingAs( $this->user )->postJson(
            route( 'api.login' ),
            [ 'email' => $this->user->email, 'password' => $new_pass ]
        );

        $response->assertBadRequest();
    }

    public function test_system_removes_old_resets()
    {
        Mail::fake();

        // 1. Create multiple password resets
        $reset = PasswordReset::createForUser( $this->user );
        $reset = PasswordReset::createForUser( $this->user );
        $reset = PasswordReset::createForUser( $this->user );

        // 2. Reset password        
        $new_pass = $this->faker->password( 12 );
        $response = $this->actingAs( $this->user )->postJson(
            route( 'api.reset-password', $reset->code ),
            [ 'password' => $new_pass, 'password_confirmation' => $new_pass ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseEmpty( 'password_resets' );
    }

    protected function afterRefreshingDatabase()
    {
        // Seed database
        $this->user = User::factory()->create();
    }
}
