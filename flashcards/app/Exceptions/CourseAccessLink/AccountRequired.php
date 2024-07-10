<?php

namespace App\Exceptions\CourseAccessLink;

use Exception;

class AccountRequired extends Exception
{
    public function render( $request )
    {
        return response()->error( 
            'An account is required to view this course',
            403,
            required_action: 'login'
        );
    }
}
