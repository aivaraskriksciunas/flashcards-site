<?php 

namespace App\Services\Importing;

use App\Exceptions\Import\DeckImportException;
use App\Models\Deck;
use App\Models\Flashcard;

class QuizletImport extends SetImporter {

    /**
     * Take a string and convert it into a list of Flashcards
     *
     * @param string $set
     * @return Flashcard[]
     */
    public function parse_set( string $set ): array
    {
        $pairs = explode( "\n", $set ); // Create a list of pairs
        $flashcards = [];

        if ( count( $pairs ) == 0 ) {
            throw new DeckImportException( 'The provided deck does not contain any word pairs.' );
        }

        for ( $i = 0; $i < count( $pairs ); $i++ )
        {
            $pair = explode( "\t", $pairs[$i] );

            if ( count( $pair ) != 2 ) {
                throw new DeckImportException( "Error parsing word pair #" . ( $i + 1 ) . ". Make sure that the question and answer are separated by a tab." );
            }

            $card = new Flashcard();
            $card->question = $pair[0];
            $card->answer = $pair[1];
            $flashcards[] = $card;
        }

        return $flashcards;
    }

}