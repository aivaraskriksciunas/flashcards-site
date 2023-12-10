<?php

namespace App\Models;

use App\Models\EmailConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Authorizable, HasFactory, HasApiTokens;

    const PASSWORD_LOGIN_TOKEN = 'browser-token';
    const GOOGLE_LOGIN_TOKEN = 'google-token';

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'is_admin', 'remember_token',
    ];

    protected $casts = [
        'is_valid' => 'boolean'
    ];

    public $timestamps = true;

    protected function password() : Attribute
    {
        return Attribute::make(
            set: fn ( $value ) => Hash::make( $value )
        );
    }

    public function checkPassword( string $pass ) : bool 
    {
        return Hash::check( $pass, $this->password );
    }

    public function decks() {
        return $this->hasMany( Deck::class );
    }

    public function quizzes() {
        return $this->hasMany( Quiz::class );
    }

    public function notifications() {
        return $this->hasMany( Notification::class );
    }

    public function forumPosts() {
        return $this->hasMany( ForumPost::class );
    }

    public function forumComments() {
        return $this->hasMany( ForumComment::class );
    }

    public function emailConfirmations() {
        return $this->hasMany( EmailConfirmation::class );
    }

    public function getLibrary() 
    {
        return Library::where( 'user_id', $this->id )
            // ->where( 'parent_id', null )
            ->firstOr( function() { return $this->createLibrary(); } );
    }

    public function createLibrary() 
    {
        $library = new Library([ 'name' => $this->name . " Library" ]);
        $library->user()->associate( $this );
        $library->save();

        return $library;
    }
}
