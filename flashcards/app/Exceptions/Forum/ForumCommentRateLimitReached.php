<?php

namespace App\Exceptions\Forum;

use Illuminate\Support\Facades\Request;
use Exception;

class ForumCommentRateLimitReached extends Exception
{
    public function render( $request )
    {
        $max_post_count = env( 'FORUM_COMMENTS_PER_DAY' );
        return response()
            ->error( "You have reached the limit of $max_post_count comments per day. Please wait 24 hours before commenting again.", 403 );
    }
}
