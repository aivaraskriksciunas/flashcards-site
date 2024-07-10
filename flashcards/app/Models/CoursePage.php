<?php

namespace App\Models;

use App\Enums\CoursePageType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePage extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title', 
        'order',
        'type'
    ];

    protected $attributes = [
        'type' => CoursePageType::Page,
    ];

    protected $casts = [
        'type' => CoursePageType::class
    ];

    public function course() {
        return $this->belongsTo( Course::class );
    }

    public function coursePageItems() {
        return $this->hasMany( CoursePageItem::class, 'course_page_id' );
    }

    public function courseSessionPages() {
        return $this->hasMany( CourseSessionPage::class );
    }

    /**
     * Creates a page from the provided attribute array
     * If order is not set, sets this page as the last in the course
     *
     * @return CoursePage
     */
    public function createPageItem( array $attributes ) 
    {
        if ( empty( $attributes['order' ] ) ) 
        {
            $attributes['order'] = $this->coursePageItems()->count() + 1;
        }

        return $this->coursePageItems()->create( $attributes );
    }

    /**
     * Reorders the pages according to the given order in the array
     * 
     * @param array $pageIds array of page ids in order in which they should be set
     * @return void
     */
    public function setPageItemOrder( array $itemIds )
    {
        $items = $this->coursePageItems()
            ->whereIn( 'id', $itemIds )
            ->get();

        // Ensure items are sorted according to how the user provided
        $items = $items->sortBy( fn ( CoursePageItem $item ) => array_search( $item->id, $itemIds ) )->values();

        // Pages that were not provided in the array
        $remaining_items = $items->diff( $this->coursePageItems()->get() );

        $items->concat( $remaining_items ); // Merge all into one array

        foreach ( $items as $index => $item ) {
            $item->order = $index + 1;
        }

        $this->coursePageItems()->saveMany( $items );
    }
}
