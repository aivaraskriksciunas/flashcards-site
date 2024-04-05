<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CoursePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LimiterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_cannot_exceed_course_limit(): void
    {
        $user = User::factory()->create();
        Course::factory()->for( $user )->create();

        config([ 'tiers.default.courses.count' => 1 ]);

        $response = $this->actingAs( $user )->postJson(
            route( 'api.courses.store', ),
            Course::factory()->make()->toArray()
        );

        $response->assertForbidden();

        $this->assertDatabaseCount( 'courses', 1 );
    }

    public function test_cannot_exceed_course_page_limit(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $pages = CoursePage::factory()->for( $course )->count( 10 )->create();

        config([ 'tiers.default.courses.pages' => 10 ]);

        $response = $this->actingAs( $user )->postJson(
            route( 'api.courses.course_pages.store', $course ),
            CoursePage::factory()->make()->toArray()
        );

        $response->assertForbidden();

        $this->assertDatabaseCount( 'course_pages', 10 );
    }
}
