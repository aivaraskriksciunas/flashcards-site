<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAdminTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $seed = false;

    public function test_admin_login()
    {
        $password = 'admin';
        $email = $this->faker->email();

        // Create user in database
        $user = new \App\Models\User([
            'email' => $email,
            'password' => $password,
            'name' => 'Testing',
            'account_type' => User::USER_ADMIN,
        ]);
        $user->save();

        $response = $this->post( route( 'login' ), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
    }

    public function test_cannot_login_nonadmin()
    {
        $password = 'admin';
        $email = $this->faker->email();

        // Create user in database
        $user = new User([
            'email' => $email,
            'password' => $password,
            'name' => 'Testing',
            'account_type' => User::USER_STUDENT,
        ]);
        $user->save();

        $response = $this->post( route( 'login' ), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertBadRequest();
        $this->assertGuest();
    }

    public function test_login_for_user_with_subaccounts()
    {
        $password = 'admin';
        $email = $this->faker->email();

        // Create user in database
        $parent = new User([
            'email' => $email,
            'password' => $password,
            'name' => 'Testing',
            'account_type' => User::USER_STUDENT,
        ]);
        $parent->save();

        // Create admin subaccount
        $sub = new User([
            'account_type' => User::USER_ADMIN,
            'name' => 'Testing',
        ]);
        $parent->subAccounts()->save( $sub );

        $response = $this->post( route( 'login' ), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs( $sub );
    }
}
