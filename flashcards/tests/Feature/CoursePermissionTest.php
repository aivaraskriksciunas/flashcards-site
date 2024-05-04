<?php

namespace Tests\Feature;

use App\Enums\CourseVisibility;
use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CoursePageItem;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CoursePermissionTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private User $owner;
    private User $other;
    private Course $course;
    private CoursePage $page;
    private CoursePageItem $item;

    /**
     * Course permissions
     */

    public function test_cannot_get_course(): void
    {
        $this->doRequest( 'api.courses.show' );
    }

    public function test_cannot_update_course(): void
    {
        $this->doRequest( 'api.courses.update', Course::factory()->make()->toArray() );
    }

    public function test_cannot_delete_course() 
    {
        // $this->doRequest( 'api.courses.destroy' );
    }

    public function test_cannot_reorder_pages() 
    {
        $this->doRequest( 'api.courses.set-page-order', [ 'pages' => [ $this->page->id ] ] );
    }

    public function test_course_update_permissions_for_organizations(): void
    {
        $org = Organization::factory()->create();
        $owner = User::factory()->for( $org )->create();
        $this->course->user()->associate( $owner );
        $this->course->save();
        
        $orgadmin = User::factory()->for( $org )->orgAdmin()->create();
        $orgmanager = User::factory()->for( $org )->orgManager()->create();
        $orgmember = User::factory()->for( $org )->create();

        $this->course->update([ 'visibility' => CourseVisibility::Private ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgAdmin ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner, $orgadmin ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgManager ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgmember, $this->other ],
            success_users:[ $owner, $orgadmin, $orgmanager ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgEveryone ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgmember, $this->other ],
            success_users:[ $owner, $orgadmin, $orgmanager ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::Public ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgmember, $this->other ],
            success_users:[ $owner, $orgadmin, $orgmanager ],
            data:Course::factory()->make()->toArray(),
        );
    }

    public function test_course_update_permissions(): void
    {
        $org = Organization::factory()->create();
        $owner = User::factory()->create();
        $this->course->user()->associate( $owner );
        $this->course->save();
        
        $orgadmin = User::factory()->for( $org )->orgAdmin()->create();
        $orgmanager = User::factory()->for( $org )->orgManager()->create();
        $orgmember = User::factory()->for( $org )->create();

        $this->course->update([ 'visibility' => CourseVisibility::Private ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgAdmin ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgManager ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgEveryone ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ],
            data:Course::factory()->make()->toArray(),
        );

        $this->course->update([ 'visibility' => CourseVisibility::Public ]);
        $this->runValidation( 'api.courses.update', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ],
            data:Course::factory()->make()->toArray(),
        );
    }

    /**
     * Course page permissions
     */

    public function test_read_course_page_permissions(): void
    {
        $org = Organization::factory()->create();
        $owner = User::factory()->for( $org )->create();
        $this->course->user()->associate( $owner );
        $this->course->save();
        
        $orgadmin = User::factory()->for( $org )->orgAdmin()->create();
        $orgmanager = User::factory()->for( $org )->orgManager()->create();
        $orgmember = User::factory()->for( $org )->create();

        $this->course->update([ 'visibility' => CourseVisibility::Private ]);
        $this->runValidation( 'api.courses.course_pages.show', 
            fail_users:[ $orgadmin, $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner ] 
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgAdmin ]);
        $this->runValidation( 'api.courses.course_pages.show', 
            fail_users:[ $orgmanager, $orgmember, $this->other ],
            success_users:[ $owner, $orgadmin ] 
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgManager ]);
        $this->runValidation( 'api.courses.course_pages.show', 
            fail_users:[ $orgmember, $this->other ],
            success_users:[ $owner, $orgadmin, $orgmanager ] 
        );

        $this->course->update([ 'visibility' => CourseVisibility::OrgEveryone ]);
        $this->runValidation( 'api.courses.course_pages.show', 
            fail_users:[ $this->other ],
            success_users:[ $owner, $orgadmin, $orgmanager, $orgmember ] 
        );

        $this->course->update([ 'visibility' => CourseVisibility::Public ]);
        $this->runValidation( 'api.courses.course_pages.show', 
            fail_users:[],
            success_users:[ $owner, $orgadmin, $orgmanager, $orgmember, $this->other  ] 
        );
    }

    public function test_cannot_create_course_page(): void
    {
        $this->doRequest( 'api.courses.course_pages.store', CoursePage::factory()->make()->toArray() );
    }

    public function test_cannot_update_course_page(): void
    {
        $this->doRequest( 'api.courses.course_pages.update', CoursePage::factory()->make()->toArray() );
    }

    public function test_cannot_delete_course_page(): void
    {
        // $this->doRequest( 'api.courses.course_pages.destroy' );
    }

    public function test_cannot_reorder_course_page_items() 
    {
        $this->doRequest( 'api.courses.course_pages.set-page-item-order', [ 'items' => [ $this->item->id ] ] );
    }

    /**
     * Course page item permissions
     */
    
    public function test_cannot_get_course_page_item(): void
    {
        $this->doRequest( 'api.courses.course_pages.course_page_items.show', CoursePageItem::factory()->make()->toArray() );
    }

    public function test_cannot_create_course_page_item(): void
    {
        $this->doRequest( 'api.courses.course_pages.course_page_items.store', CoursePageItem::factory()->make()->toArray() );
    }

    public function test_cannot_update_course_page_item(): void
    {
        $this->doRequest( 'api.courses.course_pages.course_page_items.update', CoursePageItem::factory()->make()->toArray() );
    }

    public function test_cannot_delete_course_page_item(): void
    {
        $this->doRequest( 'api.courses.course_pages.course_page_items.destroy' );
    }


    
    private function doRequest( $route, $data = [] )
    {
        $this->runValidation( $route, [ $this->other ], [ $this->owner ], $data );
    }

    private function runValidation( $route, $fail_users = [], $success_users = [], $data = [],  )
    {
        // Get route object
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName( $route );
        if ( $route == null ) return;

        foreach ( $fail_users as $other )
        {
            $response = $this->requestAs( $route, $other, $data );
            $response->assertNotFound();
        }

        foreach ( $success_users as $success )
        {
            $response = $this->requestAs( $route, $success, $data );
            $response->assertSuccessful();
        }
    }

    private function requestAs( Route $route, User $user, $data = [] )
    {
        // Build route params
        $params = [];
        if ( strpos( $route->uri, '{course}' ) !== false ) {
            $params['course'] = $this->course;
        }
        if ( strpos( $route->uri, '{course_page}' ) !== false ) {
            $params['course_page'] = $this->page;
        }
        if ( strpos( $route->uri, '{course_page_item}' ) !== false ) {
            $params['course_page_item'] = $this->item;
        }

        // Get route
        $url = URL::toRoute( $route, $params, false );

        $request = $this->actingAs( $user );
        $response = null;
        if ( in_array( 'GET', $route->methods ) ) {
            $response = $request->getJson( $url );
        }
        else if ( in_array( 'POST', $route->methods ) ) {
            $response = $request->postJson( $url, $data );
        }
        else if ( in_array( 'PATCH', $route->methods ) ) {
            $response = $request->patchJson( $url, $data );
        }
        else if ( in_array( 'DELETE', $route->methods ) ) {
            $response = $request->deleteJson( $url );
        }
        else if ( in_array( 'PUT', $route->methods ) ) {
            $response = $request->putJson( $url, $data );
        }
        else {
            $this->fail( 'Cannot perform request' );
        }
        
        return $response;
    }

    protected function afterRefreshingDatabase()
    {
        // Seed the database
        $this->owner = User::factory()->create();
        $this->other = User::factory()->create();
        $this->course = Course::factory()->for( $this->owner )->create();
        $this->page = CoursePage::factory()->for( $this->course )->create();
        $this->item = CoursePageItem::factory()->for( $this->page )->create();
    }
}
