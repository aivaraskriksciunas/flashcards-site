<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Import\ImportAnkiRequest;
use App\Http\Requests\Import\ImportQuizletRequest;
use App\Http\Resources\Deck\DeckResource;
use App\Services\DeckService;
use App\Services\Importing\AnkiImport;
use App\Services\Importing\QuizletImport;
use Illuminate\Http\Request;

class ApiImportController extends Controller
{
    public function __construct( 
        private DeckService $deckService 
    ) {}

    public function import_quizlet_set( ImportQuizletRequest $request, QuizletImport $importService )
    {
        $flashcards = $importService->parse_set( $request->input( 'content' ) );

        $deck = $this->deckService->createEmptyDeck( $request->user(), 'Imported deck' );
        $deck->cards()->saveMany( $flashcards );

        return new DeckResource( $deck );
    }

    public function import_anki_set( ImportAnkiRequest $request, AnkiImport $importService )
    {
        $flashcards = $importService->parse_set( 
            $request->file->get(),
        );

        if ( !$request->input( 'many_decks', false ) )
        {
            // Only one deck is allowed
            $flashcards = $importService->merge_parsed_decks_into_one( $flashcards );
        }

        $decks = [];
        foreach ( $flashcards as $deck_name => $cards )
        {
            $deck = $this->deckService->createEmptyDeck( $request->user(), $deck_name );
            $deck->cards()->saveMany( $cards );
            $decks[] = $deck;
        }

        return DeckResource::collection( $decks );
    }
}
