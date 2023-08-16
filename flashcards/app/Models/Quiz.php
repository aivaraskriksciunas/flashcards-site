<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';
    public $timestamps = false;

    public function user() 
    {
        return $this->belongsTo( User::class );
    }

    public function deck() 
    {
        return $this->belongsTo( Deck::class );
    }

    public function items() 
    {
        return $this->hasMany( QuizItem::class );
    }
}
