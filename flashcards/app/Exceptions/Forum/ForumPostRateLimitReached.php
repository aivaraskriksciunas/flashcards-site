<?php

namespace App\Exceptions\Forum;

use Illuminate\Support\Facades\Request;
use Exception;

class ForumPostRateLimitReached extends Exception
{
    public function render( $request )
    {
        $max_post_count = env( 'FORUM_POSTS_PER_DAY' );
        return response()
            ->error( "You have reached the limit of $max_post_count post per day. Please wait 24 hours before posting again.", 403 );
    }
}
