<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'response', 'last_review'
    ];

    public $timestamps = true;

    public function deck() {
        return $this->belongsTo( Flashcard::class );
    } 
}
