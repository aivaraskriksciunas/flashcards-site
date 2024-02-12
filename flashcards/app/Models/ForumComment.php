<?php

namespace App\Models;

use App\Events\ForumComment\ForumCommentCreated;
use App\Events\ForumComment\ForumCommentDeleted;
use App\Events\ForumComment\ForumCommentUpdated;
use App\Models\Utils\HasActivityLogging;
use App\Services\ForumReactions\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory, Reactable, HasActivityLogging;

    protected $fillable = [
        'content'
    ];

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function forumPost()
    {
        return $this->belongsTo( ForumPost::class );
    }

}
