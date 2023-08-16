<?php 

namespace App\Services\QuizGeneration\CardRaters;

use App\Models\Flashcard;
use App\Models\FlashcardProgress;
use App\Models\User;
use App\Services\CardProgressService;
use Carbon\Carbon;

class ProgressBasedCardRater implements CardRater
{
    public function __construct( private CardProgressService $cardProgress ) {}

    public function rate_card( Flashcard $card, User $user ) : int
    {
        $rating = 1;

        $progress = $this->cardProgress->get_card_progress( $card, $user );
        if ( $progress->is_last_wrong ) {
            // If last attempt was wrong, force this card to appear in the next quiz
            $rating += 100;
        }

        if ( $progress->nwrong > $progress->nright ) {
            // Increase the chance of appearance if there are more wrong hits than right hits
            $rating += $progress->nwrong - $progress->nright;
        }

        if ( Carbon::now()->greaterThanOrEqualTo( $progress->next_revision ) ) {
            // This card should show up
            $rating += 10;
        }

        return (int)$rating;
    }
}