<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $seed = true;

    public function test_login()
    {
        $password = 'admin';
        $email = $this->faker->email();

        // Create user in database
        $user = new \App\Models\User([
            'email' => $email,
            'password' => $password,
            'name' => 'Testing',
            'is_admin' => false
        ]);
        $user->save();

        $response = $this->post( '/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus( 200 );
        $this->assertAuthenticated();

    }

    public function test_register()
    {
        $password = 'admin';
        $email = $this->faker->safeEmail();
        $response = $this->post( '/api/register', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus( 200 );
        
        $this->assertAuthenticated();
        $user = User::first();
        $this->assertFalse( $user->is_valid, 'New user should be invalid until email confirmed.' );
    }
}