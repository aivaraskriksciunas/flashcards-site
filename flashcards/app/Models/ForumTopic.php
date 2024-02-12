<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    use HasFactory, HasActivityLogging;

    protected $fillable = [
        'title', 'slug',
    ];

    public function forumPosts() {
        return $this->hasMany( ForumPost::class );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
