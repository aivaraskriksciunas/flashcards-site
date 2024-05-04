<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumAttachment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title'
    ];

    public function forumPost() 
    {
        return $this->belongsTo( ForumPost::class );
    }

    public function attachable() 
    {
        return $this->morphTo( 'attachable' );
    }
}
