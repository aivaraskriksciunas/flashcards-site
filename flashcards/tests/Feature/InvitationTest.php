<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Mail\InvitationReceivedEmail;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    private Organization $organization;
    private User $user;
    private User $member;

    public function test_creates_invitation(): void
    {
        Mail::fake();

        config([ 'auth.invitation_timeout' => 60 ]);

        $email = $this->faker->email;
        $name = $this->faker->name;
        $response = $this->actingAs( $this->user )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $email,
                'name' => $name,
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertCreated();

        $this->assertDatabaseCount( 'invitations', 1 );
        $inv = Invitation::first();
        $this->assertEquals( $email, $inv->email );
        $this->assertEquals( $name, $inv->name );
        $this->assertEquals( UserType::ORG_MEMBER, $inv->account_type );
        $this->assertEquals( $this->user->id, $inv->creator_id );
        $this->assertEquals( $this->organization->id, $inv->organization_id );
        $this->assertLessThan( Carbon::now()->addSeconds( 61 ), $inv->valid_until );

        Mail::assertSent( InvitationReceivedEmail::class );
    }

    public function test_other_user_cannot_create_invitation()
    {
        Mail::fake();

        $user2 = User::factory()->create();
        $response = $this->actingAs( $user2 )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $this->faker()->email,
                'name' => $this->faker()->name,
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertForbidden();
        Mail::assertNothingSent();
    }

    public function test_member_cannot_create_invitation()
    {
        $response = $this->actingAs( $this->member )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $this->faker()->email,
                'name' => $this->faker()->name,
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertForbidden();
    }

    public function test_cannot_exceed_member_limit()
    {
        config([ 'tiers.default.organizations.users' => 2 ]);

        $response = $this->actingAs( $this->user )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $this->faker()->email,
                'name' => $this->faker()->name,
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseCount( 'invitations', 0 );
    }

    public function test_cannot_exceed_member_limit_with_invitations()
    {
        config([ 'tiers.default.organizations.users' => 3 ]);
        $this->createInvitation();

        Mail::fake();
        $response = $this->actingAs( $this->user )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $this->faker()->email,
                'name' => $this->faker()->name,
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseCount( 'invitations', 1 );
        Mail::assertNothingSent();
    }

    public function test_cannot_access_expired_invitation()
    {
        $invitation = $this->createInvitation();
        $invitation->valid_until = Carbon::now()->subDays( 1 );
        $invitation->save();
        
        $response = $this->actingAs( $this->user )->getJson(
            route( 'api.invitation.show', $invitation )
        );

        $response->assertNotFound();
    }

    public function test_can_get_members_and_invitations()
    {
        $response = $this->actingAs( $this->user )->getJson(
            route( 'api.organizations.members.show' )
        );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->user )->getJson(
            route( 'api.organizations.invitations.show' )
        );
        $response->assertSuccessful();
    }

    public function test_simple_member_cannot_get_member_list()
    {
        $response = $this->actingAs( $this->member )->getJson(
            route( 'api.organizations.members.show' )
        );
        $response->assertForbidden();

        $response = $this->actingAs( $this->member )->getJson(
            route( 'api.organizations.invitations.show' )
        );
        $response->assertForbidden();
    }

    public function test_student_cannot_get_member_list()
    {
        $student = User::factory()->create();
        $response = $this->actingAs( $student )->getJson(
            route( 'api.organizations.members.show' )
        );
        $response->assertForbidden();

        $response = $this->actingAs( $student )->getJson(
            route( 'api.organizations.invitations.show' )
        );
        $response->assertForbidden();
    }

    public function test_does_not_contain_invitation_code()
    {
        $invitation = $this->createInvitation();
        $response = $this->actingAs( $this->user )->getJson(
            route( 'api.organizations.invitations.show' )
        );

        $json = collect( $response->json() );
        $values = $json->flatten()->values();
        $this->assertNotContains( $invitation->code, $values, 'Response should not contain invitation code.' );
    }

    public function test_can_accept_invitation()
    {
        $invitation = $this->createInvitation();

        $response = $this->postJson(
            route( 'api.invitation.accept', $invitation ),
            [ 'password' => $this->faker()->password() ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseCount( 'invitations', 0 );
        $member = User::where( 'email', $invitation->email )->first();
        $this->assertNotNull( $member );
        $this->assertEquals( $invitation->name, $member->name );
        $this->assertEquals( $invitation->account_type, $member->account_type );
    }

    public function test_existing_user_can_accept_invitation()
    {
        $outside_user = User::factory()->create();
        $invitation = $this->createInvitation([ 'email' => $outside_user->email ]);

        $response = $this->postJson(
            route( 'api.invitation.accept', $invitation ),
            [ 'password' => $this->faker()->password() ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseCount( 'invitations', 0 );

        $subaccounts = $outside_user->subAccounts()->get();
        $this->assertCount( 1, $subaccounts, 'Subaccount should be created' );
        
        $member = $subaccounts[0];
        $this->assertEquals( $invitation->name, $member->name );
        $this->assertEquals( $invitation->account_type, $member->account_type );
    }

    public function test_cannot_create_two_members_with_same_email_in_organization()
    {
        // Test that it cannot create two identical invitations
        $taken_email = $this->faker->email();
        $this->createInvitation([ 'email' => $taken_email ]);

        $response = $this->actingAs( $this->user )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $taken_email,
                'name' => $this->faker()->name(),
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertBadRequest();
        $this->assertDatabaseCount( 'invitations', 1 );

        // Test that it cannot create invitation for existing user
        $response = $this->actingAs( $this->user )->postJson( 
            route( 'api.invitation.create' ),
            [
                'email' => $this->member->email,
                'name' => $this->faker()->name(),
                'account_type' => UserType::ORG_MEMBER,
            ]
        );

        $response->assertBadRequest();
        $this->assertDatabaseCount( 'invitations', 1 );
    }   

    protected function afterRefreshingDatabase()
    {
        // Seed database
        $this->organization = Organization::factory()->create();
        $this->user = User::factory()->for( $this->organization )->orgAdmin()->create();
        $this->member = User::factory()->for( $this->organization )->orgMember()->create();
    }

    private function createInvitation( $attributes = [] )
    {
        return Invitation::factory()
            ->for( $this->user, 'creator' )
            ->for( $this->organization )
            ->create( $attributes );
    }
}
