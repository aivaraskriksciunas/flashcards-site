<?php

namespace App\Models;

use App\Services\ForumReactions\ForumReactions;
use App\Services\ForumReactions\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory, Reactable;

    protected $fillable = [
        'title', 'content'
    ];

    public function user() 
    {
        return $this->belongsTo( User::class );
    }

    public function forumTopic()
    {
        return $this->belongsTo( ForumTopic::class );
    }

    public function comments()
    {
        return $this->hasMany( ForumComment::class );
    }
}
