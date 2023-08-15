<?php

namespace App\Models;

use App\Services\ForumReactions\ForumReactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReaction extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => ForumReactions::class,
    ];

    public function user() 
    {
        return $this->belongsTo( User::class );
    }

    public function reactable()
    {
        return $this->morphTo( 'reactable' );
    }
}
