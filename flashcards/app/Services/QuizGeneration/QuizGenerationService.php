<?php 

namespace App\Services\QuizGeneration;

use App\Models\Deck;
use App\Models\Quiz;
use App\Models\Flashcard;
use App\Models\User;
use Carbon\Carbon;
use App\Services\QuizGeneration\CardRaters\CardRater;

class QuizGenerationService 
{

    const DEFAULT_QUIZ_SIZE = 10;

    public function __construct( 
        private CardRater $card_rater
    ) {}

    /**
     * Retrieves the last unfinished quiz, or creates a new one
     *
     * @param Deck $deck
     * @param User $user
     * @return Quiz
     */
    public function get_or_generate( Deck $deck, User $user )
    {
        $quiz = $deck->quizzes()->whereNull( 'date_taken' )->first();

        if ( !$quiz ) {
            $quiz = $this->generate_quiz( $deck, $user );
        }

        return $quiz;
    }

    /**
     * Generates a quiz for a given deck for a given user
     *
     * @param Deck $deck
     * @param User $user
     * @return Quiz
     */
    public function generate_quiz( Deck $deck, User $user )
    {
        $cards = $this->get_card_ratings( $deck->cards, $user );
        
        $chosen_cards = $this->choose_cards( $cards, $this->get_quiz_size() );

        $quiz = new Quiz();
        $quiz->date_generated = Carbon::now();
        $quiz->user()->associate( $user );
        $quiz->deck()->associate( $deck );
        $quiz->save();

        $quiz_items = [];
        foreach ( $chosen_cards as $card )
        {
            $quiz->items()->create([
                'flashcard_id' => $card
            ]);
        }

        return $quiz;
    }

    /**
     * Retrieve a list of rated cards
     *
     * @param array $cards
     * @param User $user
     * @return array
     */
    private function get_card_ratings( $cards, User $user )
    {
        $ratings = [];

        foreach ( $cards as $card )
        {
            $ratings[$card->id] = $this->card_rater->rate_card( $card, $user );
        }

        return $ratings;
    }

    /**
     * Uses weighted random algorithm to select m items from an array
     *
     * @param array $card_weights
     * @param integer $card_count
     * @return array
     */
    private function choose_cards( array $card_weights, int $card_count )
    {
        $weight_sum = 0;
        foreach ( $card_weights as $card ) {
            $weight_sum += $card;
        }

        $chosen_cards = [];
        while ( count( $chosen_cards ) < $card_count && $weight_sum > 0 )
        {
            $choice = mt_rand( 0, $weight_sum );
            foreach ( $card_weights as $card_id => $weight ) 
            {
                $choice -= $weight;
                if ( $choice <= 0 ) 
                {
                    $chosen_cards[] = $card_id;
                    $weight_sum -= $weight;
                    // Set this card's weight to 0 to prevent it being chosen again
                    $card_weights[$card_id] = 0;
                    break;
                }
            }
        }

        return $chosen_cards;
    }
    
    /**
     * Returns number of cards per quiz
     *
     * @return int
     */
    private function get_quiz_size() 
    {
        return QuizGenerationService::DEFAULT_QUIZ_SIZE;
    }
}