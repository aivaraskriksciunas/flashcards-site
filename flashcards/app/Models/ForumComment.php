<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use App\Services\ForumReactions\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumComment extends Model
{
    use HasFactory, Reactable, HasActivityLogging, SoftDeletes;

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
