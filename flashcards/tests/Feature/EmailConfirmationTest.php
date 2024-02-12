<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\EmailConfirmation;

class EmailConfirmationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_verify_user()
    {
        $response = $this->post( '/api/register', [
            'email' => $this->faker->email,
            'name' => $this->faker->name(),
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseCount( 'users', 1 );
        $user = User::first();
        $this->assertFalse( $user->is_valid, 'New user should be invalid.' );
        $this->assertDatabaseCount( 'email_verifications', 1 );
        $conf = EmailConfirmation::first();

        $response = $this->get( route( 'api.verify.email', $conf->verification_code ) );
        $response->assertSuccessful();

        $user->refresh();
        $this->assertTrue( $user->is_valid, 'User should be valid after confirmation.' );
    }
}
