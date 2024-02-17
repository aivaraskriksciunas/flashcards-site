<?php

namespace App\Models;

use App\Enums\FlashcardType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'answer', 'comment',
        'question_type', 'answer_type'
    ];

    protected $casts = [
        'question_type' => FlashcardType::class,
        'answer_type' => FlashcardType::class
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
            'question_type' => $this->question_type,
            'answer' => $this->answer,
            'answer_type' => $this->answer_type,
        ];
    }

    private function getAQPair() {
        return [
            'question' => $this->answer,
            'question_type' => $this->answer_type,
            'answer' => $this->question,
            'answer_type' => $this->question_type,
        ];
    }
}
