<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardProgress extends Model
{
    use HasFactory;

    protected $table = 'flashcard_progress';

    protected $fillable = [
        'flashcard_id', 'user_id'
    ];

    public $timestamps = false;

    public function user() 
    {
        return $this->belongsTo( User::class );
    }

    public function card() 
    {
        return $this->belongsTo( Flashcard::class, 'flashcard_id' );
    }
}
