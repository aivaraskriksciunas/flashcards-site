<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class Deck extends Model
{
    use HasFactory, SoftDeletes, HasActivityLogging, HasUlids;

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    protected static function booted(): void
    {
        static::saved( function ( Deck $deck ) {
            $deck->removeDraft();
        });
    }

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function cards() {
        return $this->hasMany( Flashcard::class );
    }

    public function quizzes() {
        return $this->hasMany( Quiz::class );
    }

    public function libraries() {
        return $this->belongsToMany( Library::class, 'library_decks' )
            ->withPivot( 'created_at', 'last_view_at' );
    }

    public function attachments() {
        return $this->morphMany( ForumAttachment::class, 'attachable' );
    }

    /**
     * Stores request in redis to be used as a draft
     *
     * @param array $data
     * @return void
     */
    public function setDraft( array $data ) 
    {
        Redis::set( 
            $this->getRedisDraftKey(), 
            json_encode( $data ), 
            'EX', 15 * 24 * 60 * 60  // 15 Days
        );
    }

    /** 
     * Retrieves stored draft from Redis
     * 
     * @return mixed
     */
    public function getDraft() 
    {
        return Redis::get( $this->getRedisDraftKey() );
    }

    /**
     * Clears drafts for this user for this deck
     *
     * @return void
     */
    public function removeDraft()
    {
        Redis::del( $this->getRedisDraftKey() );
    }

    private function getRedisDraftKey() {
        if ( !request()->user() ) {
            return null;
        }
        
        $user_id = request()->user()->id;
        return "draft:$user_id:{$this->id}";
    }

    /**
     * Overrides the default resolution functionality provided by HasUlids
     * This functinality allows mixed keys in the database, not strictly ulid format
     *
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return parent::resolveRouteBindingQuery($query, $value, $field);
    }
    
    /**
     * Checks if the deck is already in the provided library
     *
     * @param User|Library $library library to check. If passed user, will first get the users library.
     * @return boolean
     */
    public function isInLibrary( User|Library $library ): bool
    {
        if ( $library instanceof User ) {
            $library = $library->getLibrary();
        }

        return $library->decks()->where( 'id', $this->id )->exists();
    }
}
