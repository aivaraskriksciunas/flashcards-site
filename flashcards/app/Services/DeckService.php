<?php 

namespace App\Services;

use App\Enums\FlashcardType;
use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\User;

class DeckService 
{
    public function createEmptyDeck( User $user, string $name )
    {
        $deck = $user->decks()->create([
            'name' => $name,
        ]);

        $user->getLibrary()->decks()->attach( $deck );

        return $deck;
    }

    public function updateCards( Deck $deck, array $cards )
    {
        $cards_to_update = $this->getDeckCardList( $deck );

        foreach ( $cards as $card )
        {
            if ( !isset( $card['id'] ) || !$card['id'] || !array_key_exists( $card['id'], $cards_to_update ) )
            {
                // Create card
                $this->createCard( $deck, $card );
                continue;
            }

            // Find the equivalent of this card, that is already stored in the database
            $db_card = $cards_to_update[$card['id']];
            if ( $this->isSameCard( $db_card, $card ) )
            {
                // Update card
                Flashcard::where( 'id', $card['id'] )
                    ->update([ 
                        'question' => $card['question'], 
                        'answer' => $card['answer'],
                        'question_type' => $card['question_type'] ?? FlashcardType::Text,
                        'answer_type' => $card['answer_type'] ?? FlashcardType::Text,
                        'comment' => $card['comment'] ?? null,
                    ]);

                // Remove this card from the queue
                unset( $cards_to_update[$card['id']]);
            }
            else 
            {
                // Create card
                $this->createCard( $deck, $card );
            }
        }

        // Remove remaining cards
        Flashcard::destroy( array_keys( $cards_to_update ) );
    }

    /**
     * Creates a copy of the given deck and attributes it to the user
     *
     * @param User $user 
     * @param Deck $deck
     * @return Deck
     */
    public function cloneDeck( User $user, Deck $deck ): Deck
    {
        $copy = $deck->replicate([ 'created_at', 'updated_at' ]);
        $copy->user()->associate( $user );
        $copy->save();

        foreach ( $deck->cards as $card )
        {
            $copied_card = $card->replicate([ 'created_at', 'updated_at' ]);
            $copy->cards()->save( $copied_card );
        }

        return $copy;
    }

    private function getDeckCardList( Deck $deck ) 
    {
        $list = [];
        foreach ( $deck->cards as $card )
        {
            $list[$card->id] = [
                'question' => $card->question,
                'answer' => $card->answer,
                'comment' => $card->comment,
            ];
        }

        return $list;
    }

    private function isSameCard( $card1, $card2 )
    {
        return strcmp( $card1['question'], $card2['question'] ) == 0 &&
            strcmp( $card1['answer'], $card2['answer'] ) == 0;
    }

    private function createCard( Deck $deck, $card )
    {
        $flashcard = new Flashcard();
        $flashcard->question = $card['question'];
        $flashcard->answer = $card['answer'];
        $flashcard->question_type = $card['question_type'] ?? FlashcardType::Text;
        $flashcard->answer_type = $card['answer_type'] ?? FlashcardType::Text;
        $deck->cards()->save( $flashcard );
    }
}