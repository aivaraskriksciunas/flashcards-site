<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Deck extends Model
{
    use HasFactory, HasActivityLogging;

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    protected static function booted(): void
    {
        static::saved(function ( Deck $deck ) {
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
        $user_id = request()->user()->id;
        return "draft:$user_id:{$this->id}";
    }
}
