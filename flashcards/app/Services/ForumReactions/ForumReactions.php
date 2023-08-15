<?php 

namespace App\Services\ForumReactions;

enum ForumReactions: string {
    case Upvote = '1';
    case Downvote = '-1';
}