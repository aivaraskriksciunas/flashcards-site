<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
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
}