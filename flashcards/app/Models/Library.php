<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory, HasActivityLogging;

    protected $fillable = [
        'name'
    ];

    public function user() 
    {
        return $this->belongsTo( User::class );
    }

    // public function libraries() 
    // {
    //     return $this->hasMany( Library::class );
    // }

    public function decks() 
    {
        return $this->belongsToMany( Deck::class, 'library_decks' )
            ->withPivot( 'created_at', 'last_view_at' );
    }
}
