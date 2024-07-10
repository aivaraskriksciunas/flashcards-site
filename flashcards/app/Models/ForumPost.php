<?php

namespace App\Models;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Utils\HasActivityLogging;
use App\Services\ForumReactions\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ForumPost extends Model
{
    use HasFactory, Reactable, HasActivityLogging, SoftDeletes;

    protected $fillable = [
        'title', 'content'
    ];

    protected static function booted(): void
    {
        static::creating(function ( ForumPost $post ) {
            $post->slug = $post->makeSlug();
        });
    }

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

    public function attachments()
    {
        return $this->hasMany( ForumAttachment::class );
    }

    public function makeSlug()
    {
        $title_slug = Str::of( $this->title )->slug( '-' )->limit( 80 );
        $slug = $title_slug;
        
        while ( ForumPost::withTrashed()->where( 'slug', $slug )->exists() ) {
            $slug = $title_slug . '-' . random_int( 10000, 1000000 );
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function content(): Attribute {
        return Attribute::make(
            get: fn ( string|null $value ) => $value,
            set: fn ( string|null $value ) => $this->purifyContentHTML( $value ),
        );
    }

    private function purifyContentHTML( string|null $content ): string {
        if ( $content == null ) return '';

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'h2,h3,ul,ol,li,strong,b,i,a[href],p');

        $purifier = new HTMLPurifier( $config );
        return $purifier->purify( $content );
    }
}
