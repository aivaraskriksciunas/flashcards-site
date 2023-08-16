<?php

namespace App\Exceptions\Quiz;

use Illuminate\Support\Facades\Request;
use Exception;

class QuizItemAlreadyAnswered extends Exception
{
    public function render( $request )
    {
        return response()->error( 'Quiz item has already been answered' );
    }
}
