<?php

namespace App\Models;

use App\Enums\UserType;
use App\Models\EmailConfirmation;
use App\Models\Utils\HasActivityLogging;
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

    const USER_ADMIN = 'admin';
    const USER_STUDENT = 'student';
    const USER_ORG_ADMIN = 'orgadmin';
    const ROLES = [ 
        User::USER_ADMIN,
        User::USER_STUDENT,
        User::USER_ORG_ADMIN
    ];

    const PASSWORD_LOGIN_TOKEN = 'browser-token';
    const GOOGLE_LOGIN_TOKEN = 'google-token';
    const SWITCH_ACCOUNT_LOGIN_TOKEN = 'switch-token';
    const INVITATION_LOGIN_TOKEN = 'invitation-token';

    protected $fillable = [
        'name', 'email', 'password', 'account_type',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'account_type' => UserType::class,
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

    public function parentAccount() {
        return $this->belongsTo( User::class, 'parent_id' );
    }

    public function subAccounts() {
        return $this->hasMany( User::class, 'parent_id' );
    }

    public function organization() {
        return $this->belongsTo( Organization::class );
    }

    public function isAdmin() {
        return $this->account_type === UserType::ADMIN;
    }

    public function isOrgAdmin() {
        return $this->account_type === UserType::ORG_ADMIN;
    }

    public function isOrgManager() {
        return $this->account_type === UserType::ORG_ADMIN || 
            $this->account_type === UserType::ORG_MANAGER;
    }

    public function isStudent() {
        return $this->account_type === UserType::STUDENT;
    }

    public function userLogs() {
        return $this->hasMany( UserLog::class );
    }

    public function courses() {
        return $this->hasMany( Course::class );
    }

    public function assignedCourses() {
        return $this->belongsToMany( Course::class, 'assigned_user_courses' )
            ->withTimestamps()
            ->withPivot([ 'assigned_by' ])
            ->using( AssignedUserCourse::class );
    }

    protected function isValid() : Attribute {
        return Attribute::make(
            get: function ( bool $value ) {
                if ( $this->parentAccount ) {
                    return $this->parentAccount->is_valid;
                }

                return $value;
            },
            set: fn( bool $value ) => $value,
        );
    }

    /**
     * Returns parent account or $this, if this is the parent account
     *
     * @return User
     */
    public function getParentAccount() 
    {
        return $this->parentAccount ?? $this;
    }

    /**
     * Get a collection with the user and its subaccounts
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllAccounts() 
    {
        $parent = $this->getParentAccount();
        return $parent->subAccounts->prepend( $parent );
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
