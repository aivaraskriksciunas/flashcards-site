<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $seed = false;

    public function test_login()
    {
        $password = 'admin';
        $email = $this->faker->email();

        // Create user in database
        $user = new \App\Models\User([
            'email' => $email,
            'password' => $password,
            'name' => 'Testing',
            'account_type' => User::USER_STUDENT,
        ]);
        $user->save();

        $response = $this->post( '/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus( 200 );

    }

    public function test_register()
    {
        $password = 'admin';
        $email = $this->faker->safeEmail();
        $response = $this->post( '/api/register', [
            'email' => $email,
            'name' => $this->faker->name(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus( 200 );
        
        $this->assertAuthenticated();
        $user = User::where( 'email', $email )->first();
        $this->assertFalse( $user->is_valid, 'New user should be invalid until email confirmed.' );
        $this->assertEquals( $user->account_type, User::USER_STUDENT, 'New user should be a student.' );
    }

    public function test_register_with_existing_email()
    {
        $admin = User::factory()->admin()->create();
        $response = $this->post( '/api/register', [
            'email' => $admin->email,
            'name' => $this->faker->name(),
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertBadRequest();
        $this->assertEquals( 1, DB::table( 'users' )->count() );
    }

    public function test_adds_student_account_for_admin()
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs( $admin )->post( 
            '/api/accounts/add/student',
            []
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'users', 2 );
        $related_student = $admin->subAccounts()->first();
        $this->assertEquals( User::USER_STUDENT, $related_student->account_type, 'Sub account should be student account' );
    }

    public function test_prevents_two_student_subaccounts_when_parent_student()
    {
        $student = User::factory()->create();
        $response = $this->actingAs( $student )->post( 
            route( 'api.accounts.add.student' ),
            []
        );

        $response->assertBadRequest();
        $this->assertDatabaseCount( 'users', 1 );
    }

    public function test_prevents_two_student_subaccounts_when_subaccount_student()
    {
        // Create an admin parent account
        $admin = User::factory()->admin()->create();
        $sub = User::factory()->create([ 'parent_id' => $admin->id ]);

        $response = $this->actingAs( $admin )->post( 
            route( 'api.accounts.add.student' ),
            []
        );

        $response->assertBadRequest();
        $this->assertDatabaseCount( 'users', 2 );
    }

    public function test_switches_user_account()
    {
        // Create an admin parent account
        $admin = User::factory()->admin()->create();
        $sub = User::factory()->create([ 'parent_id' => $admin->id ]);

        $response = $this->actingAs( $admin )->get(
            route( 'api.accounts.switch', [ 'user' => $sub->id ] )
        );

        $response->assertSuccessful();
    }

    public function test_does_not_switch_to_stranger_account()
    {
        // Create an admin parent account
        $parent = User::factory()->create();
        $sub = User::factory()->create([ 'parent_id' => $parent->id ]);

        // Create stranger's account
        $stranger = User::factory()->create();

        $response = $this->actingAs( $parent )->get(
            route( 'api.accounts.switch', [ 'user' => $stranger->id ])
        );

        $response->assertNotFound();
    }

    public function test_shows_all_subaccounts()
    {
        $parent = User::factory()->create();
        $sub1 = User::factory()->create([ 'parent_id' => $parent->id ]);
        $sub2 = User::factory()->create([ 'parent_id' => $parent->id ]);

        $response = $this->actingAs( $parent )->get(
            '/api/user'
        );

        $data = $response->json();
        $this->assertArrayHasKey( 'accounts', $data );
        $this->assertCount( 3, $data['accounts'], 'Should show all subaccounts when going through parent' );

        $response = $this->actingAs( $sub1 )->get(
            '/api/user'
        );

        $data = $response->json();
        $this->assertArrayHasKey( 'accounts', $data );
        $this->assertCount( 3, $data['accounts'], 'Should show all subaccounts when going through child' );
    }
}
