<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function cards() {
        return $this->hasMany( Flashcard::class );
    }

    public function libraries() {
        return $this->belongsToMany( Library::class, 'library_decks' )
            ->withPivot( 'created_at', 'last_view_at' );
    }
}
