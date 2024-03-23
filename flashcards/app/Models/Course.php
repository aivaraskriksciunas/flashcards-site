<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUlids, HasActivityLogging;

    protected $fillable = [
        'title'
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
}
