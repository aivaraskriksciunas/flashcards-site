<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    use HasFactory;

    public $fillable = [ 'flashcard_id' ];
    public $timestamps = false;

    public function quiz() 
    {
        return $this->belongsTo( Quiz::class );
    }

    public function card()
    {
        return $this->belongsTo( Flashcard::class, 'flashcard_id' );
    }
}
