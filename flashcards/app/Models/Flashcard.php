<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'answer', 'comment',
    ];

    public $timestamps = true;

    public function deck() {
        return $this->belongsTo( Flashcard::class );
    } 

    /**
     * Returns an array with question/answer pair, with possibility to swap them
     *
     * @param string $mode Allowed values: 'qa', 'aq', 'mixed'
     * @return void
     */
    public function formatQAPair( ?string $mode ) {

        if ( $mode === 'aq' ) {
            return $this->getAQPair();
        }
        else if ( $mode === 'mixed' ) {
            // Choose order randomly
            return rand( 0, 10 ) % 2 ? $this->getQAPair() : $this->getAQPair();
        }
        
        return $this->getQAPair();
    }

    private function getQAPair() {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }

    private function getAQPair() {
        return [
            'question' => $this->answer,
            'answer' => $this->question,
        ];
    }
}
