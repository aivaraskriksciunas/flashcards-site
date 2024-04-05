<?php

namespace Tests\Feature;

use App\Enums\CourseVisibility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Organization;

class CourseParameterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sets_course_parameters()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();

        $response = $this->actingAs( $user )->patchJson( 
            route( 'api.courses.update', $course ),
            [
                'title' => 'Hey title',
                'visibility' => CourseVisibility::OrgManager,
                'is_unlocked' => true,
            ]
        );

        $response->assertSuccessful();
        $course->refresh();
        $this->assertEquals( CourseVisibility::OrgManager, $course->visibility );
        $this->assertEquals( true, $course->is_unlocked );
        $this->assertEquals( 'Hey title', $course->title );

        $response = $this->actingAs( $user )->patchJson( 
            route( 'api.courses.update', $course ),
            [
                'visibility' => CourseVisibility::OrgAdmin,
                'is_unlocked' => false,
            ]
        );


        $response->assertSuccessful();
        $course->refresh();
        $this->assertEquals( CourseVisibility::OrgAdmin, $course->visibility );
        $this->assertEquals( false, $course->is_unlocked );
    }

    public function test_non_owner_cannot_modify_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create([ 'visibility' => CourseVisibility::Public, 'is_unlocked' => true ]);

        $other = User::factory()->create();
        $response = $this->actingAs( $other )->patchJson( 
            route( 'api.courses.update', $course ),
            [
                'visibility' => CourseVisibility::OrgAdmin,
                'is_unlocked' => false,
            ]
        );

        $response->assertNotFound();
        $course->refresh();
        $this->assertEquals( CourseVisibility::Public, $course->visibility );
        $this->assertEquals( true, $course->is_unlocked );
    }

    public function test_other_org_members_cannot_modify_private_course()
    {
        $org = Organization::factory()->create();
        $user = User::factory()->for( $org )->create();
        $course = Course::factory()->for( $user )->create([ 'visibility' => CourseVisibility::Private, 'is_unlocked' => true ]);

        $otherAdmin = User::factory()->for( $org )->orgAdmin()->create();
        $otherManager = User::factory()->for( $org )->orgManager()->create();
        $otherMember = User::factory()->for( $org )->create();

        foreach([ $otherAdmin, $otherManager, $otherMember ] as $other )
        {
            $response = $this->actingAs( $other )->patchJson( 
                route( 'api.courses.update', $course ),
                [
                    'visibility' => CourseVisibility::OrgAdmin,
                    'is_unlocked' => false,
                ]
            );
    
            $response->assertNotFound();
            $course->refresh();
            $this->assertEquals( CourseVisibility::Private, $course->visibility );
            $this->assertEquals( true, $course->is_unlocked );
        }
    }
}
