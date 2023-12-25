<?php

namespace Tests\Feature;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class OrganizationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_invalid_org_admin()
    {
        $admin = User::factory()->orgAdmin()->create();
        $request = $this->actingAs( $admin )->get(
            '/api/library'
        );

        $request->assertBadRequest();
        $this->assertArrayHasKey( 'required_action', $request->json(), 'Should return required action to solve issue.' );
    }

    public function test_adds_organization() 
    {
        $orgadmin = User::factory()->orgAdmin()->create();

        $companyName = $this->faker->company();
        $request = $this->actingAs( $orgadmin )->post(
            route( 'api.register.organization' ),
            [
                'name' => $companyName,
                'type' => $this->faker->randomElement([ 'for-profit', 'non-profit', 'education', 'government' ]),
            ]
        );

        $request->assertSuccessful();
        $orgadmin->refresh();

        $this->assertNotNull( $orgadmin->organization_id, 'Should assign an organization to a user' );
        $this->assertEquals( $companyName, $orgadmin->organization->name, 'Added organization should match the one provided.' );
        $this->assertDatabaseCount( 'organizations', 1 );
    }

    public function test_does_not_add_org_if_user_has_one() 
    {
        $org = Organization::factory()->create();
        $orgadmin = User::factory()
            ->orgAdmin()
            ->for( $org )
            ->create();

        $request = $this->actingAs( $orgadmin )->post(
            route( 'api.register.organization' ),
            [
                'name' => $this->faker->company(),
                'type' => $this->faker->randomElement([ 'for-profit', 'non-profit', 'education', 'government' ]),
            ]
        );

        $request->assertBadRequest();
        $orgadmin->refresh();

        $this->assertNotNull( $orgadmin->organization_id, 'User should still have an organization assigned.' );
        $this->assertEquals( $org->id, $orgadmin->organization->id, 'User should retain the old organization info.' );
        $this->assertDatabaseCount( 'organizations', 1 );
    }

    public function test_does_not_add_if_invalid_type() 
    {
        $orgadmin = User::factory()->orgAdmin()->create();

        $request = $this->actingAs( $orgadmin )->postJson(
            route( 'api.register.organization' ),
            [
                'name' => $this->faker->company(),
                'type' => 'bla bla',
            ]
        );

        $request->assertUnprocessable();
        $orgadmin->refresh();

        $this->assertNull( $orgadmin->organization_id, 'No organization should be added' );
        $this->assertDatabaseCount( 'organizations', 0 );
    }
}
