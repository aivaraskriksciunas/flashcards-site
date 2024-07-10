<?php

namespace Tests\Feature;

use App\Enums\CourseAccessLinkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseAccessLink;
use App\Models\CoursePage;
use App\Models\CoursePageItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CourseAccessLinkTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $owner;
    private User $guest;
    private Course $course;
    private Collection $coursePages;

    public function test_creates_access_link(): void
    {
        $response = $this->actingAs( $this->owner )->postJson(
            route( 'api.courses.access-links.store', $this->course->id ), 
            [
                'is_anonymous' => true,
                'name' => $this->faker->name(),
                'type' => CourseAccessLinkType::RequireAccount,
            ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'course_access_links', 1 );
    }

    public function test_cannot_create_link_for_different_course(): void 
    {
        $response = $this->actingAs( $this->guest )->postJson(
            route( 'api.courses.access-links.store', $this->course->id ), 
            [
                'is_anonymous' => true,
                'name' => $this->faker->name(),
                'type' => CourseAccessLinkType::RequireAccount,
            ]
        );

        $response->assertNotFound();
        $this->assertDatabaseCount( 'course_access_links', 0 );
    }

    public function test_cannot_set_expire_date_to_past(): void 
    {
        $response = $this->actingAs( $this->owner )->postJson(
            route( 'api.courses.access-links.store', $this->course->id ), 
            [
                'name' => $this->faker->name(),
                'type' => CourseAccessLinkType::RequireAccount,
                'expires_at' => Carbon::now()->subDays( 2 ),
            ]
        );

        $response->assertUnprocessable();
        $this->assertDatabaseCount( 'course_access_links', 0 );
    }

    public function test_can_modify_access_link(): void 
    {
        $link = $this->createCourseLink( CourseAccessLinkType::RequireAccount );

        $new_name = $this->faker()->words( 3, true );
        $new_expires = Carbon::now()->addDays( 3 )->milliseconds( 0 );

        $response = $this->actingAs( $this->owner )->patchJson(
            route( 'api.courses.access-links.update', [ 'course' => $this->course->id, 'access_link' => $link->id ]), 
            [
                'type' => CourseAccessLinkType::Unrestricted,
                'name' => $new_name,
                'expires_at' => $new_expires,
            ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'course_access_links', 1 );
        $link->refresh();
        $this->assertEquals( $new_name, $link->name, 'Name should be changed' );
        $this->assertEquals( CourseAccessLinkType::Unrestricted, $link->type );
        $this->assertEquals( $new_expires, $link->expires_at );
    }

    public function test_cannot_modify_system_generated_access_link(): void 
    {
        $link = $this->course->getPublicAccessLink();

        $new_name = $this->faker()->words( 3, true );
        $new_expires = Carbon::now()->addDays( 3 )->milliseconds( 0 );

        $response = $this->actingAs( $this->owner )->patchJson(
            route( 'api.courses.access-links.update', [ 'course' => $this->course->id, 'access_link' => $link->id ]), 
            [
                'type' => CourseAccessLinkType::RequireAccount,
                'name' => $new_name,
                'expires_at' => $new_expires,
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseCount( 'course_access_links', 1 );
        $link->refresh();
        $this->assertNotEquals( $new_name, $link->name, 'Name should not be changed' );
        $this->assertNotEquals( CourseAccessLinkType::RequireAccount, $link->type );
        $this->assertNotEquals( $new_expires, $link->expires_at );
    }

    public function test_can_remove_expire_date(): void 
    {
        $link = $this->createCourseLink( CourseAccessLinkType::RequireAccount );

        $new_name = $this->faker()->words( 3, true );

        $response = $this->actingAs( $this->owner )->patchJson(
            route( 'api.courses.access-links.update', [ 'course' => $this->course->id, 'access_link' => $link->id ]), 
            [
                'type' => CourseAccessLinkType::Unrestricted,
                'name' => $new_name,
                'expires_at' => null,
            ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'course_access_links', 1 );
        $link->refresh();
        $this->assertEquals( $new_name, $link->name, 'Name should be changed' );
        $this->assertEquals( CourseAccessLinkType::Unrestricted, $link->type );
        $this->assertNull( $link->expires_at );
    }

    public function test_non_owner_cannot_modify_link(): void 
    {
        $link = $this->createCourseLink( CourseAccessLinkType::RequireAccount );

        $new_name = $this->faker()->words( 3, true );
        $new_expires = Carbon::now()->addDays( 3 )->milliseconds( 0 );

        $response = $this->actingAs( $this->guest )->patchJson(
            route( 'api.courses.access-links.update', [ 'course' => $this->course->id, 'access_link' => $link->id ]), 
            [
                'name' => $new_name,
                'type' => CourseAccessLinkType::Unrestricted,
                'expires_at' => $new_expires,
            ]
        );

        $response->assertNotFound();
        $this->assertDatabaseCount( 'course_access_links', 1 );
        $link->refresh();
        $this->assertNotEquals( $new_name, $link->name, 'Name should be changed' );
        $this->assertEquals( CourseAccessLinkType::RequireAccount, $link->type );
        $this->assertNull( $link->expires_at );
    }

    public function test_can_delete(): void 
    {
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted );

        $response = $this->actingAs( $this->owner )->deleteJson(
            route( 'api.courses.access-links.destroy', [ 'course' => $this->course->id, 'access_link' => $link->id ] )
        );

        $response->assertNoContent();
        $this->assertDatabaseCount( 'course_access_links', 0 );
    }

    public function test_cannot_delete_system_links(): void 
    {
        $link = $this->course->getPublicAccessLink();
        $this->assertDatabaseCount( 'course_access_links', 1 );

        $response = $this->actingAs( $this->owner )->deleteJson(
            route( 'api.courses.access-links.destroy', [ 'course' => $this->course->id, 'access_link' => $link->id ] )
        );

        $response->assertForbidden();
        $this->assertDatabaseCount( 'course_access_links', 1 );
    }

    public function test_non_owner_cannot_delete(): void 
    {
        $link = $this->createCourseLink( CourseAccessLinkType::RequireAccount );

        $response = $this->actingAs( $this->guest )->deleteJson(
            route( 'api.courses.access-links.destroy', [ 'course' => $this->course->id, 'access_link' => $link->id ] )
        );

        $response->assertNotFound();
        $this->assertDatabaseCount( 'course_access_links', 1 );
    }

    public function afterRefreshingDatabase()
    {
        $this->owner = User::factory()->create();
        $this->guest = User::factory()->create();
        $this->course = Course::factory()->for( $this->owner )->create();
        $this->coursePages = CoursePage::factory()->for( $this->course )->createMany( 10 );
        $this->coursePages->each( fn ( $page ) => CoursePageItem::factory()->for( $page )->createMany( 3 ) );
    }

    private function createCourseLink( CourseAccessLinkType $type = CourseAccessLinkType::Unrestricted, ?Carbon $expires = null ): CourseAccessLink
    {
        $link = new CourseAccessLink([
            'name' => $this->faker->word(),
            'type' => $type,
            'expires_at' => $expires
        ]);

        $link->course()->associate( $this->course );
        $link->user()->associate( $this->owner );
        $link->save();
        return $link;
    }
}
