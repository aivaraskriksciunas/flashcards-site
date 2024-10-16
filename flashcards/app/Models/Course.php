<?php

namespace App\Models;

use App\Enums\CourseAccessLinkType;
use App\Enums\CourseVisibility;
use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Course extends Model
{
    use HasFactory, HasUlids, HasActivityLogging;

    protected $fillable = [
        'title', 'visibility', 'is_unlocked',
    ];

    protected $casts = [
        'visibility' => CourseVisibility::class,
        'is_unlocked' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function coursePages() {
        return $this->hasMany( CoursePage::class );
    }

    public function assignedUsers() {
        return $this->belongsToMany( User::class, 'assigned_user_courses' )
            ->withTimestamps()
            ->withPivot([ 'assigned_by' ])
            ->using( AssignedUserCourse::class );
    }

    public function attachments() {
        return $this->morphMany( ForumAttachment::class, 'attachable' );
    }

    public function courseAccessLinks() {
        return $this->hasMany( CourseAccessLink::class );
    }

    public function accessLinks() {
        return $this->courseAccessLinks();
    }

    public function courseSessions() {
        return $this->hasMany( CourseSession::class );
    }

    /**
     * Creates a page from the provided attribute array
     * If order is not set, sets this page as the last in the course
     *
     * @return CoursePage
     */
    public function createPage( array $attributes ) 
    {
        if ( empty( $attributes['order' ] ) ) 
        {
            $attributes['order'] = $this->coursePages()->count() + 1;
        }

        return $this->coursePages()->create( $attributes );
    }

    /**
     * Reorders the pages according to the given order in the array
     * 
     * @param array $pageIds array of page ids in order in which they should be set
     * @return void
     */
    public function setPageOrder( array $pageIds )
    {
        $pages = $this->coursePages()
            ->whereIn( 'id', $pageIds )
            ->get();

        // Ensure pages are sorted according to how the user provided
        $pages = $pages->sortBy( fn ( CoursePage $page ) => array_search( $page->id, $pageIds ) )->values();

        // Pages that were not provided in the array
        $remaining_pages = $pages->diff( $this->coursePages()->get() );

        $pages->concat( $remaining_pages ); // Merge all into one array

        foreach ( $pages as $index => $page ) {
            $page->order = $index + 1;
        }

        $this->coursePages()->saveMany( $pages );
    }

    /**
     * Retrieves or creates a public link of the course.
     * Only retrieves a system-generated link while ignoring those generated by users.
     *
     * @return CourseAccessLink
     */
    public function getPublicAccessLink() : CourseAccessLink
    {
        $link = $this->accessLinks()
            ->where( 'type', CourseAccessLinkType::Unrestricted )
            ->where( 'user_created', false )
            ->first();

        if ( !$link ) {
            $link = new CourseAccessLink([
                'type' => CourseAccessLinkType::Unrestricted,
                'user_created' => false,
                'name' => 'Public link',
            ]);
            $link->user()->associate( $this->user );
            $this->accessLinks()->save( $link );
        }

        return $link;
    }

    /**
     * Retrieves or creates an access link of the course.
     * Only retrieves a system-generated link while ignoring user ones.
     * Used for getting a link for organization members.
     *
     * @return CourseAccessLink
     */
    public function getAccessLinkForAssignedUsers() : CourseAccessLink
    {
        $link = $this->accessLinks()
            ->where( 'type', CourseAccessLinkType::AssignedOnly )
            ->where( 'user_created', false )
            ->first();

        if ( !$link ) {
            $link = new CourseAccessLink([
                'type' => CourseAccessLinkType::AssignedOnly,
                'user_created' => false,
                'name' => 'Organization link',
            ]);
            $link->user()->associate( $this->user );
            $this->accessLinks()->save( $link );
        }

        return $link;
    }
}
