<?php 

namespace App\Services\QuizGeneration\CardRaters;

use App\Models\Flashcard;
use App\Models\User;

interface CardRater 
{
    public function rate_card( Flashcard $card, User $user );
}