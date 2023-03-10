<?php 

namespace App\Services;

use App\Models\Deck;
use App\Models\Flashcard;

class DeckService 
{
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
                        'answer' => $card['answer'] 
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

    private function getDeckCardList( Deck $deck ) 
    {
        $list = [];
        foreach ( $deck->cards as $card )
        {
            $list[$card->id] = [
                'question' => $card->question,
                'answer' => $card->answer,
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
        $deck->cards()->save( $flashcard );
    }
}