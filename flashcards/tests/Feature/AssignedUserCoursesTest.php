<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignedUserCoursesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Organization $organization;
    private User $orgAdmin;
    private User $orgManager;
    private User $member;
    private Course $course;

    public function test_can_assign_member(): void
    {
        $response = $this->actingAs( $this->orgAdmin )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => [ $this->member->id ] ] );
        $response->assertSuccessful();

        $this->course->refresh();
        $this->assertCount( 1, $this->course->assignedUsers );
    }

    public function test_can_assign_multiple_members(): void
    {
        $members = User::factory( 10 )->for( $this->organization )->create();
        $ids = $members->pluck( 'id' )->values()->all();
        
        $response = $this->actingAs( $this->orgAdmin )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => $ids ] );
        $response->assertSuccessful();

        $this->course->refresh();
        $this->assertCount( 10, $this->course->assignedUsers );
    }

    public function test_can_only_assign_user_once()
    {
        // Add the same user twice
        $response = $this->actingAs( $this->orgAdmin )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => [ $this->member->id ] ] );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->orgAdmin )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => [ $this->member->id ] ] );
        $response->assertSuccessful();

        $this->course->refresh();
        $this->assertCount( 1, $this->course->assignedUsers );
    }

    public function test_only_managers_can_assign_users()
    {
        $member2 = User::factory()->for( $this->organization )->orgMember()->create();
        $member3 = User::factory()->for( $this->organization )->orgMember()->create();

        $response = $this->actingAs( $this->orgAdmin )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => [ $this->member->id ] ] );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->orgManager )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => [ $member2->id ] ] );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->member )
            ->post( route( 'api.courses.assigned.add', $this->course ), [ 'user_ids' => [ $member3->id ] ] );
        $response->assertNotFound();
        
        $this->course->refresh();
        $this->assertCount( 2, $this->course->assignedUsers );
    }

    protected function afterRefreshingDatabase()
    {
        // Seed database
        $this->organization = Organization::factory()->create();
        $this->orgAdmin = User::factory()->for( $this->organization )->orgAdmin()->create();
        $this->orgManager = User::factory()->for( $this->organization )->orgManager()->create();
        $this->member = User::factory()->for( $this->organization )->orgMember()->create();
        $this->course = Course::factory()->for( $this->orgAdmin )->create();
    }
}
