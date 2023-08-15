<?php

namespace App\Models;

use App\Services\ForumReactions\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory, Reactable;

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
