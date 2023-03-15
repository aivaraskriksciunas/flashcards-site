<?php 

namespace App\Services\QuizGeneration\CardRaters;

use App\Models\Flashcard;
use App\Models\User;

class DefaultCardRater implements CardRater
{
    public function rate_card( Flashcard $card, User $user )
    {
        return 1;
    }
}