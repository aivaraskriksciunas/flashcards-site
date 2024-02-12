<?php

namespace App\Exceptions\Course;

use Exception;

class CoursePageNotFound extends Exception
{
    public function __construct( private string $pageId )
    {}

    public function render( $request )
    {
        return response()
            ->error( "Course page with ID {$this->pageId} does not exist.", 404 );
    }
}
