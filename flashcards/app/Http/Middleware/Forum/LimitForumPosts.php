<?php

namespace App\Http\Middleware\Forum;

use App\Exceptions\Forum\ForumPostRateLimitReached;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LimitForumPosts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle( Request $request, Closure $next )
    {
        // Limit number of posts per day
        $date = Carbon::now()->subDay( 1 );
        $post_count = $request->user()->forumPosts()->where( 'created_at', '>=', $date )->count();
        $max_post_count = env( 'FORUM_POSTS_PER_DAY' );

        if ( $post_count >= $max_post_count )
        {
            throw new ForumPostRateLimitReached();
        }

        return $next($request);
    }
}
