<?php 

namespace App\Services;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\User;

class DeckSummaryService 
{
    public function __construct( private CardProgressService $cardProgressService ) {}

    public function getDeckSummary( Deck $deck, User $user )
    {
        $cards = $deck->cards()->get();
        $res = [];

        foreach ( $cards as $card )
        {
            $progress = $this->cardProgressService->get_card_progress( $card, $user );
            $res[$card->id] = [
                'question' => $card->question,
                'answer' => $card->answer,
                'next_revision' => $progress->next_revision,
                'ncorrect' => $progress->ncorrect,
                'nwrong' => $progress->nwrong,
                'perc_correct' => $progress->ncorrect / max( 1, $progress->ncorrect + $progress->nwrong ),
            ];
        }
        
        return $res;
    }

} 